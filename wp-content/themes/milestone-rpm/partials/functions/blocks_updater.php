<?php 

$version = '2.0.1';

// ======================================= //
//             ERROR LOGGING               //
// ======================================= // 

function spr_log_wpdb_prepare($query, ...$args) {
	global $wpdb;
	foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $trace) {
		if (!empty($trace['file']) && strpos($trace['file'], 'blocks_updater.php') !== false) {
			// $message = "[QUERY] " . print_r($query, true);
			// spr_write_to_debug_log(__FILE__, __LINE__, $message, false);
			// $message = "[PARAMS] " . print_r($args, true);
			// spr_write_to_debug_log(__FILE__, __LINE__, $message, false);
			break;
		}
	}
	return call_user_func_array([$wpdb, 'prepare'], [$query, ...$args]);
}


// ======================================= //
//                VARIABLES                //
// ======================================= // 

$block_file_path = '';
$old_structure = [];
$new_structure = [];
$snapshots = [];

$old_fields = [];
$new_fields = [];
$old_parents = [];
$new_parents = [];

$old_file_path = '';
$new_file_path = '';


// ======================================= //
//             REGISTER BLOCK              //
// ======================================= // 

/**
 * Triggers the block updater functions from each block file, including initial snapshot,
 * and updates when a change is detected
 * @param string $blockid Textual name of the block
 * @param array $acf_migration_map old migration map, deprectated and not used
 * @param $post_id single post ID used to test migration on a single post during development
 */

function spr_register_block_changes($blockid, $acf_migration_map = [], $post_id = null) {

	if (!function_exists('acf_add_local_field_group')) {
		return; // ACF not available, bail early
	}

	// $acf_migration_map is deprecated and should not appear anywhere in
	// this new block updater's codebase

	// ACF field types that should be excluded from migration mapping.
	// These fields typically act as layout wrappers or UI elements (e.g., tabs, groups)
	// and do not represent data that needs to be preserved or migrated.
	$excluded_migratable_types = ['tab', 'group', 'accordion'];

	global $block_file_path;
	global $old_file_path;
	global $new_file_path;
	global $old_structure;
	global $new_structure;

	global $old_fields;
	global $new_fields;
	global $old_parents;
	global $new_parents;

	$snapshots = get_option('spr_snapshot_' . $blockid, []);
	$latest_snapshot = end($snapshots);

	if (isset($_GET['m']) && $_GET['m'] === 'test') {
		$mode = 'dev';
	} else {
		$mode = 'live';
	}

	if ($mode == 'dev') {
		// ========== DEV MODE =========== //
		$block_file_path = get_template_directory() . '/partials/core/mild/block_text/block_text.acf';
		$old_structure = spr_load_block_structure_from_file($block_file_path);
		$new_file_path = get_template_directory() . '/partials/test/block_text/block_text.acf';
		$new_structure = spr_load_block_structure_from_file($new_file_path);
	} elseif ($mode == 'live') {
		// ========== LIVE MODE =========== //
		$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
		$block_file_path = $trace[0]['file'] ?? null;
		$new_file_path = $block_file_path;
		if ($latest_snapshot !== false) {
			$old_structure = $latest_snapshot['structure'];
		}
		$new_structure = spr_load_block_structure_from_file($block_file_path);
	}

	// Only store a new snapshot if structure has changed or if none exist for the block
	if ($latest_snapshot === false) {
		$message = "writing initial snapshot for " . $blockid;
		spr_write_to_debug_log(__FILE__, __LINE__, $message, false);
		if (isset($_GET['m']) && $_GET['m'] === 'test' && isset($_GET['o'])) {
			echo('<pre>'); print_r('no snapshot found — writing initial snapshot'); echo('</pre>');
		}
		if (!wp_next_scheduled('spr_schedule_snapshot', [$blockid, $block_file_path])) {
			wp_schedule_single_event(time() + 5, 'spr_schedule_snapshot', [$blockid, $block_file_path]);
		}

	} elseif (!spr_compare_acf_structures($latest_snapshot['structure'], $new_structure)) {
		$message = "structural change detected in " . $blockid;
		spr_write_to_debug_log(__FILE__, __LINE__, $message, false);
		if (isset($_GET['m']) && $_GET['m'] === 'test' && isset($_GET['o'])) {
			echo('<pre>'); print_r('structure changed — writing new snapshot with migration map'); echo('</pre>');
		}
		spr_flatten_acf_fields($latest_snapshot['structure']['fields'], [], $old_fields, []);
		spr_flatten_acf_fields($new_structure['fields'], [], $new_fields, []);
		$acf_migrations_map = spr_generate_migration_map($blockid, $latest_snapshot['structure'], $new_structure, $old_fields, $new_fields, $excluded_migratable_types);
		spr_store_block_snapshot($blockid, $new_file_path, $acf_migrations_map);
		$snapshots = get_option('spr_snapshot_' . $blockid, []);
		$latest_snapshot = end($snapshots);
		$latest_snapshot_key = array_key_last($snapshots);
		if (isset($_GET['m']) && $_GET['m'] === 'test') {
			if (isset($_GET['post_id'])) {
				$post_id = intval($_GET['post_id']);
				echo('<pre>'); print_r('about to run migrations on ' . $blockid . ' for post_id ' . $post_id); echo('</pre>');
			} else {
				$post_id = null;
				echo('<pre>'); print_r('about to run migrations on ' . $blockid . ' for all posts'); echo('</pre>');
			}
		}
		if (!wp_next_scheduled('spr_run_scheduled_block_migration', [$blockid, $post_id])) {
			$message = "scheduling migration for " . $blockid;
			spr_write_to_debug_log(__FILE__, __LINE__, $message, false);
			wp_schedule_single_event(time() + 5, 'spr_run_scheduled_block_migration', [$blockid, $post_id]);
		}
	} else {
		if (isset($_GET['m']) && $_GET['m'] === 'test' && isset($_GET['o'])) {
			echo('<pre>'); print_r('snapshot already exists! snapshot count: ' . count($snapshots)); echo('</pre>');
		}
	}

	if (isset($_GET['m']) && $_GET['m'] === 'test') {
		if (isset($_GET['o']) && $_GET['o'] === 'snapshots') {
			echo('<pre>'); print_r($snapshots); echo('</pre>');
		}
		if (isset($_GET['o']) && $_GET['o'] === 'snapshot') {
			echo('<pre>'); print_r($latest_snapshot); echo('</pre>');
		}
	}

	if (isset($_GET['m']) && $_GET['m'] === 'test') {
		if (isset($_GET['o']) && $_GET['o'] === 'map') {
			if (isset($acf_migrations_map) && count($acf_migrations_map) > 0) {
				echo('<pre>'); print_r('migration generated in $acf_migrations_map'); echo('</pre>');
				echo('<pre>'); print_r($acf_migrations_map); echo('</pre>');
			} else {
				echo('<pre>'); print_r('migration pulled from latest snapshot:'); echo('</pre>');
				$snapshots = get_option('spr_snapshot_' . $blockid, []);
				$latest_snapshot = end($snapshots);
				echo('<pre>'); print_r($latest_snapshot['migration']); echo('</pre>');
			}
		}
	}

}


// ======================================= //
//         HANDLE SCHEDULED SNAPSHOT       //
// ======================================= //

/**
 * Handles the scheduled snapshot for a block.
 *
 * @param string $blockid The block ID to snapshot.
 * @param string $block_file_path The block's file path
 */

function spr_handle_scheduled_snapshot($blockid, $block_file_path) {
	if (!empty($blockid) && !empty($block_file_path)) {
		spr_store_block_snapshot($blockid, $block_file_path);
	}
}
add_action('spr_schedule_snapshot', 'spr_handle_scheduled_snapshot', 10, 2);


// ======================================= //
//        HANDLE SCHEDULED MIGRATION       //
// ======================================= //

/**
 * Executes a deferred block migration in the background.
 *
 * @param string $blockid   The block ID to migrate (e.g. 'block_text').
 * @param int    $post_id   Optional post ID to scope the migration.
 */

function spr_run_scheduled_block_migration_handler($blockid, $post_id) {
	// $message = "about to run migrations for " . $blockid;
	// spr_write_to_debug_log(__FILE__, __LINE__, $message, false);
	$snapshots = get_option('spr_snapshot_' . $blockid, []);
	$latest_snapshot_key = array_key_last($snapshots);
	$latest_snapshot = $snapshots[$latest_snapshot_key] ?? [];
	$migration_result = spr_run_block_migrations($blockid, $post_id);
	if (!empty($migration_result['migration'])) {
		$message = "migration complete for " . $blockid;
		spr_write_to_debug_log(__FILE__, __LINE__, $message, false);
		$latest_snapshot['migration'] = array_merge(
			[ 'from' => $migration_result['plan']['from'], 'to' => $migration_result['plan']['to'] ],
			$migration_result['migration'], [ 'results' => $migration_result['plan']['results'] ]
		);
		$snapshots[$latest_snapshot_key] = $latest_snapshot;
		update_option('spr_snapshot_' . $blockid, $snapshots);
		$message = "migration results added to " . $blockid . "'s snapshot";
		spr_write_to_debug_log(__FILE__, __LINE__, $message, false);
	}
}
add_action('spr_run_scheduled_block_migration', 'spr_run_scheduled_block_migration_handler', 10, 2);


// ======================================= //
//        BUILD ARRAY FROM ACF FILE        //
// ======================================= // 

/**
 * Extract the array passed to acf_add_local_field_group() from a .acf file.
 * @param string $file_path Absolute path to the .acf file.
 * @return array|null Returns the parsed array, or null on failure.
 */

function spr_extract_array_from_acf_file($file_path) {

	if(!file_exists($file_path)) {
		$message = "ACF file not found at $file_path";
		spr_write_to_debug_log(__FILE__, __LINE__, $message, false);
		return null;
	}

	$contents = file_get_contents($file_path);

	// Match the array(...) inside acf_add_local_field_group(...)
	$pattern = '/acf_add_local_field_group\s*\(\s*(array\s*\(.*\))\s*\)\s*;/s';

	if(!preg_match($pattern, $contents, $matches)) {
		$message = "Could not extract array from ACF file at $file_path";
		spr_write_to_debug_log(__FILE__, __LINE__, $message, false);
		return null;
	}

	$array_code = $matches[1];

	// Suppress errors in eval and validate result
	try {
		$structure = eval('return ' . $array_code . ';');
	} catch (Throwable $e) {
		$message = "Failed to eval array from ACF file at $file_path: " . $e->getMessage();
		spr_write_to_debug_log(__FILE__, __LINE__, $message, false);
		return null;
	}

	if(!is_array($structure)) {
		$message = "Extracted value from ACF file is not an array: $file_path";
		spr_write_to_debug_log(__FILE__, __LINE__, $message, false);
		return null;
	}

	return $structure;
}


// ======================================= //
//         COMPARE ACF STRUCTURES          //
// ======================================= // 

/**
 * Compares two ACF block structures deeply but ignores field order.
 * @param array $a First structure.
 * @param array $b Second structure.
 * @return bool True if structures are identical, false if changed.
 */

function spr_compare_acf_structures($a, $b) {
	// Sort fields recursively so that order doesn't affect equality
	$normalize = function($array) use (&$normalize) {
		if (is_array($array)) {
			ksort($array);
			foreach ($array as &$v) {
				$v = $normalize($v);
			}
		}
		return $array;
	};

	return $normalize($a) === $normalize($b);
}



// ======================================= //
//          STORE BLOCK STRUCTURE          //
// ======================================= // 

/**
 * Load a block definition from file and store a timestamped snapshot.
 * @param string $block_name       Block name (e.g. 'block_text').
 * @param string $block_file_path  Absolute path to the .acf file that returns the block array.
 * @return void
 */

function spr_store_block_snapshot($block_name, $block_file_path, $migration = null) {

	$structure = spr_extract_array_from_acf_file($block_file_path);

	$option_key = 'spr_snapshot_' . $block_name;
	$timestamp = date('Y-m-d');

	$existing = get_option($option_key, array());

	if(!is_array($existing)) {
		$existing = array();
	}

	$existing[$timestamp] = array(
		'structure' => $structure
	);

	if ($migration && is_array($migration)) {
		$existing[$timestamp]['migration'] = $migration;
	}

	update_option($option_key, $existing); // Save updated snapshot structure with autoload disabled
}


// ======================================= //
//        LOAD NEW BLOCK STRUCTURE         //
// ======================================= // 

/**
 * Load and return a block structure array from a .acf file.
 * @param string $file_path Absolute path to the .acf file.
 * @return array|null Returns the block array, or null on failure.
 */

function spr_load_block_structure_from_file($file_path) {
	$structure = spr_extract_array_from_acf_file($file_path);
	return $structure;
}



// ======================================= //
//           FLATTEN ACF FIELDS            //
// ======================================= // 

/**
 * Recursively flatten ACF fields into key => details map.
 * @param array $fields    ACF fields array to flatten.
 * @param array $path      Accumulated name path (e.g., ['text_cols', 'column_one']).
 * @param array $parents   Accumulated parent types (e.g., ['group', 'repeater']).
 * @param array &$output   Reference to output map.
 */

function spr_flatten_acf_fields($fields, $path, &$output_fields, $parent_chain) {
	$path = (array) $path;

	foreach ($fields as $field) {
		if (!isset($field['key']) || !isset($field['type'])) {
			continue;
		}

		$name  = $field['name'] ?? '';
		$label = $field['label'] ?? '';
		$type  = $field['type'];

		$full_path = $name ? array_merge($path, [$name]) : $path;

		$entry = [
			'name'    => $name,
			'label'   => $label,
			'type'    => $type,
			'path'    => implode('_', $full_path),
			'parents' => $parent_chain,
		];

		// Include clone targets if relevant
		if ($type === 'clone' && !empty($field['clone'])) {
			$entry['clone'] = $field['clone'];
		}

		// Include choices if defined (used for select, radio, checkbox, etc.)
		if (!empty($field['choices']) && is_array($field['choices'])) {
			$entry['choices'] = $field['choices'];
		}

		$output_fields[$field['key']] = $entry;

		// Recurse into supported container types
		if (!empty($field['sub_fields']) && is_array($field['sub_fields'])) {
			spr_flatten_acf_fields($field['sub_fields'], $full_path, $output_fields, array_merge($parent_chain, [$type]));
		} elseif ($type === 'repeater' && !empty($field['fields'])) {
			spr_flatten_acf_fields($field['fields'], array_merge($full_path, ['0']), $output_fields, array_merge($parent_chain, ['repeater']));
		} elseif ($type === 'flexible_content' && !empty($field['layouts'])) {
			foreach ($field['layouts'] as $layout) {
				if (!empty($layout['sub_fields'])) {
					$layout_path = array_merge($full_path, ['0', $layout['name']]);
					spr_flatten_acf_fields($layout['sub_fields'], $layout_path, $output_fields, array_merge($parent_chain, ['flexible_content']));
				}
			}
		}
	}
}


// ======================================= //
//       FIND BEST ACF FIELD MATCH         //
// ======================================= //

/**
 * Find the best match for a given ACF field from a pool of candidates.
 * Uses label, type, and contextual path similarity.
 * @param array $old_field Field metadata (must include key, type, label, path).
 * @param array $new_fields Array of flattened new fields keyed by field key.
 * @return array|null Best match info: ['key' => ..., 'confidence' => ..., 'trace' => [...]]
 */

function spr_find_best_acf_field_match($target_field, $search_fields) {
	$best_match = null;
	$highest_score = -1;

	$target_label = strtolower(trim($target_field['label'] ?? ''));
	$target_type = $target_field['type'] ?? null;
	$target_path = $target_field['path'] ?? '';
	$target_key = $target_field['key'] ?? null;

	foreach ($search_fields as $candidate_key => $candidate_field) {
		$candidate_field['key'] = $candidate_key;
		$candidate_label = strtolower(trim($candidate_field['label'] ?? ''));
		$candidate_type = $candidate_field['type'] ?? null;
		$candidate_path = $candidate_field['path'] ?? '';
		$candidate_key = $candidate_field['key'] ?? null;

		$score = 0;
		$trace = [];

		// Choice key similarity for select-type fields
		if (in_array($target_type, ['select', 'radio', 'checkbox'], true)
			&& in_array($candidate_type, ['select', 'radio', 'checkbox'], true)) {

			$choice_similarity = spr_choice_key_similarity($target_field, $candidate_field);
			$bonus = round($choice_similarity * 35);
			$score += $bonus;
			$trace[] = "choice key match +{$bonus}";
		}

		// Label exact match
		if ($target_label && $target_label === $candidate_label) {
			$score += 30;
			$trace[] = "label match +30";
		}

		// Generic label penalty
		$generic_labels = ['background', 'style', 'settings'];
		if ($target_label && $target_label === $candidate_label && in_array($target_label, $generic_labels, true)) {
			$score -= 15;
			$trace[] = "generic label penalty -15";
		}

		// Type match
		if ($target_type && $target_type === $candidate_type) {
			$score += 30;
			$trace[] = "type match +30";
		}

		// Partial label match
		if ($target_label && $candidate_label && str_contains($candidate_label, $target_label)) {
			$score += 5;
			$trace[] = "partial label match +5";
		}

		// Path suffix match
		if (spr_path_suffix_match($target_path, $candidate_path, 2)) {
			$score += 35;
			$trace[] = "path suffix match +35";
		}

		// Retired concept penalty
		$retired_concepts = ['pair_with'];
		$old_name = strtolower($candidate_field['name'] ?? '');
		foreach ($retired_concepts as $retired) {
			if (str_contains($old_name, $retired)) {
				$score -= 20;
				$trace[] = "retired concept penalty -20";
				break;
			}
		}

		// Jaccard similarity (shared terms, unordered)
		$jaccard = spr_path_similarity($target_path, $candidate_path);
		$jaccard_points = round($jaccard * 10);
		$score += $jaccard_points;
		$trace[] = "jaccard +{$jaccard_points}";

		// Ordered similarity (alignment of path segments)
		$ordered = spr_path_ordered_similarity($target_path, $candidate_path);
		$ordered_points = round($ordered * 25);
		$score += $ordered_points;
		$trace[] = "ordered +{$ordered_points}";

		// Shared mid-path context
		$target_parts = explode('_', strtolower($target_path));
		$candidate_parts = explode('_', strtolower($candidate_path));
		$shared_context = array_intersect($target_parts, $candidate_parts);
		$shared_terms = ['backgrounds', 'columns', 'alignment', 'settings'];
		foreach ($shared_terms as $term) {
			if (in_array($term, $shared_context, true)) {
				$score += 10;
				$trace[] = "shared path context '{$term}' +10";
				break;
			}
		}

		if ($score > $highest_score) {
			$highest_score = $score;
			$best_match = [
				'key' => $candidate_key,
				'confidence' => $score,
				'trace' => $trace,
			];
		}
	}

	return $best_match;
}



// ============================================ //
//     ACF FIELD TYPE COMPATIBILITY SCORING     //
// ============================================ //

/**
 * Calculate compatibility score between two ACF field definitions based on type.
 * @param array $source The source field (from the old structure)
 * @param array $target The target field (from the new structure)
 * @return int Compatibility score (positive or negative)
 */

function spr_get_field_type_compatibility_score($source, $target) {

	$type_from = $source['type'] ?? null;
	$type_to   = $target['type'] ?? null;

	if (!$type_from || !$type_to) return -50;
	if ($type_from === $type_to) {

		// Special handling for clone fields
		if ($type_from === 'clone') {
			$clone_from = $source['clone'] ?? null;
			$clone_to   = $target['clone'] ?? null;

			// Normalize to array
			if (!is_array($clone_from)) $clone_from = $clone_from ? [$clone_from] : [];
			if (!is_array($clone_to))   $clone_to   = $clone_to   ? [$clone_to]   : [];

			// Compare clone targets
			if (empty($clone_from) || empty($clone_to)) {
				return 25; // fallback score if clone targets not specified
			} elseif (array_intersect($clone_from, $clone_to)) {
				return 50; // full match if any clone targets match
			} else {
				return 25; // partial compatibility if type matches but targets differ
			}
		}

		return 50; // identical field types (non-clone)
	}

	$multiple_from = $source['multiple'] ?? null;
	$choices_from  = $source['choices'] ?? null;

	switch ("{$type_from}→{$type_to}") {

		case 'text→textarea':
		case 'text→wysiwyg':
		case 'textarea→wysiwyg':
		case 'wysiwyg→textarea':
			return 25;

		case 'textarea→text':
		case 'wysiwyg→text':
			return 15;

		case 'number→range':
			return 15;

		case 'select→radio':
		case 'select→button_group':
		case 'radio→select':
		case 'button_group→select':
			return ($multiple_from === false || $multiple_from === 0) ? 15 : -50;

		case 'radio→button_group':
		case 'button_group→radio':
			return 25;

		case 'true_false→checkbox':
			return (is_array($choices_from) && count($choices_from) === 1) ? 10 : -50;

		case 'checkbox→select':
			return ($multiple_from === true || $multiple_from === 1) ? 15 : -50;

		case 'checkbox→true_false':
			return (is_array($choices_from) && count($choices_from) === 1) ? 5 : -50;

		case 'post_object→relationship':
			return ($multiple_from === false || $multiple_from === 0) ? 20 : -50;

		case 'url→link':
			return 10;

		case 'file→url':
			return 10;

		case 'file→text':
			return 5;

		case 'page_link→url':
			return 20;

		case 'page_link→text':
			return 5;

		default:
			return -50;
	}
}


// ======================================= //
//        PATH SIMILARITY SCORING          //
// ======================================= //

/**
 * Calculate similarity score between two ACF paths using Jaccard similarity.
 * @param string $pathA Dotted or underscored field path.
 * @param string $pathB Dotted or underscored field path.
 * @return float Jaccard similarity score (0.0 to 1.0).
 */

function spr_path_similarity($pathA, $pathB) {
	$setA = array_unique(spr_tokenize_normalized_path($pathA));
	$setB = array_unique(spr_tokenize_normalized_path($pathB));

	$intersection = array_intersect($setA, $setB);
	$union = array_unique(array_merge($setA, $setB));

	if (count($union) === 0) {
		return 0.0;
	}

	return count($intersection) / count($union);
}


// ======================================= //
//           PATH SUFFIX MATCH             //
// ======================================= //

/**
 * Compare the trailing segments of two paths.
 * @param string $pathA
 * @param string $pathB
 * @param int $depth How many segments from the end to compare
 * @return bool True if trailing segments match
 */

function spr_path_suffix_match($pathA, $pathB, $depth = 2) {
	$partsA = array_reverse(spr_tokenize_normalized_path($pathA));
	$partsB = array_reverse(spr_tokenize_normalized_path($pathB));

	for ($i = 0; $i < $depth; $i++) {
		if (!isset($partsA[$i], $partsB[$i]) || $partsA[$i] !== $partsB[$i]) {
			return false;
		}
	}

	return true;
}


// ======================================= //
//            PATH ORDER MATCH             //
// ======================================= //

/**
 * Calculate ordered similarity between two paths.
 * Rewards matching segments in order, especially near the end.
 * @param string $pathA
 * @param string $pathB
 * @return float Score from 0.0 to 1.0
 */

function spr_path_ordered_similarity($pathA, $pathB) {
	$partsA = spr_tokenize_normalized_path($pathA);
	$partsB = spr_tokenize_normalized_path($pathB);
	
	$len = min(count($partsA), count($partsB));
	if ($len === 0) return 0.0;

	$matches = 0;
	for ($i = 0; $i < $len; $i++) {
		if ($partsA[$i] === $partsB[$i]) {
			$matches++;
		}
	}

	return $matches / $len;
}


// ======================================= //
//         TOKEN NORMALIZATION UTILS       //
// ======================================= //

/**
 * Normalize a token for comparison (e.g. singularize).
 * @param string $token
 * @return string
 */

function spr_normalize_token($token) {
	$token = strtolower(trim($token));

	// Basic stemming logic
	$replacements = [
		'/ies$/' => 'y',
		'/s$/' => '',
	];

	foreach ($replacements as $pattern => $replacement) {
		if (preg_match($pattern, $token)) {
			$token = preg_replace($pattern, $replacement, $token);
			break;
		}
	}

	return $token;
}

/**
 * Tokenize and normalize a path.
 * @param string $path
 * @return array
 */
function spr_tokenize_normalized_path($path) {
	$parts = array_filter(explode('_', strtolower($path)));
	return array_map('spr_normalize_token', $parts);
}


// ======================================= //
//           BUILD SCORING MATRIX          //
// ======================================= //

/**
 * Build a score matrix between all new and old fields.
 * @param array $new_fields
 * @param array $old_fields
 * @return array 2D array [new_key][old_key] = ['score' => int, 'trace' => []]
 */

function spr_build_match_score_matrix($new_fields, $old_fields) {
	$matrix = [];

	foreach ($new_fields as $new_key => $new_field) {
		foreach ($old_fields as $old_key => $old_field) {
			$match = spr_find_best_acf_field_match($new_field, [$old_key => $old_field]);
			$matrix[$new_key][$old_key] = $match;
		}
	}

	return $matrix;
}


// ======================================= //
//           HUNGARIAN MATCHING            //
// ======================================= //

/**
 * Run Hungarian algorithm (simplified) to find optimal 1-to-1 matches.
 * @param array $matrix Match score matrix from spr_build_match_score_matrix
 * @return array Map of new_key => old_key
 */
function spr_resolve_optimal_field_matches($matrix) {
	$assignments = [];
	$used_old_keys = [];

	foreach ($matrix as $new_key => $row) {
		uasort($row, function ($a, $b) {
			return $b['confidence'] <=> $a['confidence'];
		});

		foreach ($row as $old_key => $data) {
			if (!in_array($old_key, $used_old_keys, true)) {
				$assignments[$new_key] = $old_key;
				$used_old_keys[] = $old_key;
				break;
			}
		}
	}

	return $assignments;
}


// ======================================= //
//          CHOICE KEY SIMILARITY          //
// ======================================= //

/**
 * Evaluate similarity of choice keys between two select-like fields.
 * @param array $fieldA
 * @param array $fieldB
 * @return float Jaccard similarity score between choice keys
 */

function spr_choice_key_similarity($fieldA, $fieldB) {
	if (!isset($fieldA['choices'], $fieldB['choices']) || !is_array($fieldA['choices']) || !is_array($fieldB['choices'])) {
		return 0.0;
	}

	$keysA = array_keys($fieldA['choices']);
	$keysB = array_keys($fieldB['choices']);

	$intersection = array_intersect($keysA, $keysB);
	$union = array_unique(array_merge($keysA, $keysB));

	$similarity = count($union) ? count($intersection) / count($union) : 0.0;

	return $similarity;
}


// ======================================= //
//         GENERATE MIGRATION MAP         //
// ======================================= //

/**
 * Generate an ACF migration map between two sets of flattened fields.
 * @param string $blockid Slug or title of the block.
 * @param string $snapshot_before Timestamp of the prior snapshot.
 * @param string $snapshot_after Timestamp of the current snapshot.
 * @param array  $old_fields Flattened ACF fields from old snapshot.
 * @param array  $new_fields Flattened ACF fields from new structure.
 * @param array  $exclude_types Field types to skip (e.g., ['tab', 'group', 'accordion']).
 * @return array Structured ACF migration map.
 */

function spr_generate_migration_map($blockid, $snapshot_before, $snapshot_after, $old_fields, $new_fields, $excluded_migratable_types) {

	$message = "building migration map for " . $blockid;
	spr_write_to_debug_log(__FILE__, __LINE__, $message, false);

	$acf_migrations_map = [
		'migrations' => [],
		'unchanged' => [],
		'excluded' => [],
	];

	$filtered_new_fields = [];
	$filtered_old_fields = $old_fields;

	foreach ($new_fields as $new_key => $new_field) {
		if (in_array($new_field['type'], $excluded_migratable_types, true)) {
			$acf_migrations_map['excluded'][$new_key] = [
				'label' => $new_field['label'] ?? '',
				'path' => $new_field['path'] ?? '',
				'type' => $new_field['type'] ?? '',
				'reason' => 'excluded type'
			];
			continue;
		}

		if (isset($old_fields[$new_key])) {
			$old_field = $old_fields[$new_key];
			if (
				($new_field['type'] ?? null) === ($old_field['type'] ?? null) &&
				($new_field['path'] ?? null) === ($old_field['path'] ?? null)
			) {
				$acf_migrations_map['unchanged'][$new_key] = [
					'label' => $new_field['label'] ?? '',
					'path' => $new_field['path'] ?? '',
					'type' => $new_field['type'] ?? '',
				];
				unset($filtered_old_fields[$new_key]);
				continue;
			}
		}

		$filtered_new_fields[$new_key] = $new_field;
	}

	$matrix = spr_build_match_score_matrix($filtered_new_fields, $filtered_old_fields);
	$assignments = spr_resolve_optimal_field_matches($matrix);

	foreach ($assignments as $new_key => $old_key) {
		$match = $matrix[$new_key][$old_key];
		$acf_migrations_map['migrations'][$new_key] = [
			'label' => $filtered_new_fields[$new_key]['label'] ?? '',
			'from' => $old_key,
			'confidence' => $match['confidence'],
			'type_from' => $filtered_old_fields[$old_key]['type'] ?? null,
			'type_to' => $filtered_new_fields[$new_key]['type'] ?? null,
			'path_from' => $filtered_old_fields[$old_key]['path'] ?? null,
			'path_to' => $filtered_new_fields[$new_key]['path'] ?? null,
			'trace' => $match['trace'] ?? [],
		];
	}

	return $acf_migrations_map;
}	


// ======================================= //
//     STRUCTURE-AWARE FIELD KEY INDEXER    //
// ======================================= //

/**
 * Recursively builds a flat index of all fields in the structure, preserving parent/child links.
 *
 * @param array  $fields       ACF field array to index
 * @param string $parent_path  Concatenated meta key path
 * @param string $parent_key   Parent field key
 * @param array  &$index       Resulting flat index [field_key => info]
 */

function spr_build_field_index($fields, $parent_path = '', $parent_key = null, &$index = []) {
	foreach ($fields as $field) {
		if (!isset($field['key'], $field['name']) || !$field['name']) continue;

		$current_path = $parent_path ? $parent_path . '_' . $field['name'] : $field['name'];
		$field_key = $field['key'];

		$index[$field_key] = [
			'name'   => $field['name'],
			'key'    => $field_key,
			'path'   => $current_path,
			'parent' => $parent_key,
			'field'  => $field
		];

		if (!empty($field['sub_fields']) && is_array($field['sub_fields'])) {
			spr_build_field_index($field['sub_fields'], $current_path, $field_key, $index);
		}
	}
}


// ======================================= //
//   INSERT BUILDER FROM UPDATE HIERARCHY  //
// ======================================= //

/**
 * Traverses field hierarchy upward from update targets and populates insert[] with structural keys.
 * Adds empty 'value' rows for container types like groups and repeaters.
 *
 * @param array  &$results         The full results array to append insert rows to
 * @param array  $updates          The already built update[] array
 * @param array  $field_index      Flat field index [field_key => info] with parent chains
 * @param array  &$already_handled Tracks paths we've already inserted to avoid duplication
 */

function spr_capture_insert_keys_from_update(&$results, $updates, $field_index, &$already_handled) {
	foreach ($updates as $row) {
		$current_key = $row['new_value'];

		while ($current_key && isset($field_index[$current_key])) {
			$current_path = $field_index[$current_key]['path'];

			if (!in_array($current_path, $already_handled)) {

				$field_type = $field_index[$current_key]['field']['type'] ?? '';

				// Insert the key meta row
				$results['insert'][] = [
					'key'   => '_' . $current_path,
					'value' => $current_key
				];

				// Insert an empty value meta row if the field is a container type
				if (in_array($field_type, ['group', 'repeater', 'flexible_content', 'clone'], true)) {
					$results['insert'][] = [
						'key'   => $current_path,
						'value' => ''
					];
				}

				$already_handled[] = $current_path;
			}

			$current_key = $field_index[$current_key]['parent'];
		}
	}
}


// ======================================= //
//      RECURSIVE FIELD KEYS EXTRACTOR     //
// ======================================= //

/**
 * Recursively extracts all field keys from ACF structure fields.
 *
 * @param array $fields Array of fields from snapshot structure
 * @param array &$keys Accumulator for field keys
 * @return void
 */

function spr_extract_all_field_keys($fields, &$keys = []) {
	foreach ($fields as $field) {
		if (!isset($field['key'])) continue;

		$keys[] = $field['key'];

		if (!empty($field['sub_fields']) && is_array($field['sub_fields'])) {
			spr_extract_all_field_keys($field['sub_fields'], $keys);
		}
	}
}


// ======================================= //
//      IDENTIFY FIELDS TO BE DROPPED      //
// ======================================= //

/**
 * Identifies old field keys no longer present in the new structure or migration map,
 * maps them to their meta keys, and adds them (with underscore variants) to the drop array.
 *
 * @param array &$results Full results array with drop keys to populate
 * @param array $old_fields Fields from the old snapshot structure
 * @param array $new_fields Fields from the new snapshot structure
 * @param array $handled_field_keys Field keys already handled by update or insert
 * @param array $handled_meta_keys Meta keys already handled by update or insert
 */

function spr_capture_drop_keys(&$results, $old_fields, $new_fields, $handled_field_keys, $handled_meta_keys = []) {
	$old_keys = [];
	$new_keys = [];

	// Recursive helper to extract all field keys and build reverse map (field_key => meta_path)
	$reverse_map = [];

	$extract_and_map = function($fields, $parent_path = '') use (&$extract_and_map, &$reverse_map, &$old_keys) {
		foreach ($fields as $field) {
			if (!isset($field['key'], $field['name'])) continue;

			$current_path = $parent_path ? $parent_path . '_' . $field['name'] : $field['name'];
			$field_key = $field['key'];

			$reverse_map[$field_key] = $current_path;
			$old_keys[] = $field_key;

			if (!empty($field['sub_fields']) && is_array($field['sub_fields'])) {
				$extract_and_map($field['sub_fields'], $current_path);
			}
		}
	};

	// Extract old keys and build reverse map
	$extract_and_map($old_fields);

	// Extract new keys for existence check
	$extract_new_keys = function($fields, &$keys) use (&$extract_new_keys) {
		foreach ($fields as $field) {
			if (!isset($field['key'])) continue;
			$keys[] = $field['key'];
			if (!empty($field['sub_fields']) && is_array($field['sub_fields'])) {
				$extract_new_keys($field['sub_fields'], $keys);
			}
		}
	};

	$extract_new_keys($new_fields, $new_keys);

	$old_keys = array_unique($old_keys);
	$new_keys = array_unique($new_keys);

	// Convert handled_meta_keys to associative array for exact matching
	$handled_meta_keys_assoc = array_fill_keys($handled_meta_keys, true);

	$keys_to_drop = [];

	foreach ($old_keys as $old_key) {
		if (!in_array($old_key, $new_keys, true) && !in_array($old_key, $handled_field_keys, true)) {
			if (isset($reverse_map[$old_key])) {
				$meta_key = $reverse_map[$old_key];
				if (!isset($handled_meta_keys_assoc[$meta_key]) && !isset($handled_meta_keys_assoc['_' . $meta_key])) {
					$keys_to_drop[] = $meta_key;         // e.g. text_cols_column_one_background
					$keys_to_drop[] = '_' . $meta_key;   // e.g. _text_cols_column_one_background
				}
			} else {
				// In case no mapping found, drop by field key itself (rare)
				if (!isset($handled_meta_keys_assoc[$old_key])) {
					$keys_to_drop[] = $old_key;
				}
			}
		}
	}

	// Remove duplicates and add to results drop
	$results['drop'] = array_values(array_unique($keys_to_drop));
}


// ======================================= //
//       BUILD MIGRATION INSTRUCTIONS      //
// ======================================= //

/**
 * Builds the migration instructions for an ACF block, including fields to update, insert, or drop.
 *
 * @param string   $blockid       The block slug (e.g., 'block_text')
 * @param int|null $post_id       Optional. If provided, run migration only on that post
 * @param array    $snapshots     Array containing the before and after snapshots keyed by their timestamps
 * @return array   Result info including 'success', 'from', 'to', and 'results' arrays with instructions
 */

function spr_build_migration_plan($blockid, $post_id = null, $snapshots) {

	// Extract before and after keys and snapshots from the snapshots array
	$snapshot_keys = array_keys($snapshots);
	$snapshot_before_key = $snapshot_keys[0];
	$snapshot_after_key = $snapshot_keys[1];
	$before_snapshot = $snapshots[$snapshot_before_key];
	$after_snapshot = $snapshots[$snapshot_after_key];

	$results = [
		'update' => [],
		'insert' => [],
		'drop'   => []
	];

	$migration_map = $after_snapshot['migration']['migrations'] ?? [];
	$already_handled = [];

	$field_index = [];
	spr_build_field_index($after_snapshot['structure']['fields'] ?? [], '', null, $field_index);

	foreach ($migration_map as $new_field_key => $migration) {
		$path_from  = $migration['path_from'] ?? null;
		$path_to    = $migration['path_to'] ?? null;
		$field_from = $migration['from'] ?? null;

		if (!$path_to) continue;

		$meta_key_to = '_' . $path_to;
		
		if ($path_from && $field_from) {
			$meta_key_from = '_' . $path_from;

			// Walk the ancestry tree using field_index to build the path array
			$current_key = $new_field_key;
			$path_parts = [];
			while ($current_key && isset($field_index[$current_key])) {
				array_unshift($path_parts, $field_index[$current_key]['name']);
				$current_key = $field_index[$current_key]['parent'] ?? null;
			}

			$results['update'][] = [
				'old_key'   => $meta_key_from,
				'new_key'   => $meta_key_to,
				'old_value' => $field_from,
				'new_value' => $new_field_key,
				'path'      => $path_parts
			];
			$already_handled[] = $path_to;
			continue;
		}

		$results['insert'][] = [
			'key'   => $path_to,
			'value' => ''
		];

		$results['insert'][] = [
			'key'   => '_' . $path_to,
			'value' => $new_field_key
		];

		$already_handled[] = $path_to;
	}

	spr_capture_insert_keys_from_update($results, $results['update'], $field_index, $already_handled);

	$handled_field_keys = [];
	$handled_meta_keys = [];

	foreach ($results['update'] as $item) {
		if (!empty($item['new_value'])) {
			$handled_field_keys[] = $item['new_value'];
		}
		if (!empty($item['old_key'])) {
			$handled_meta_keys[] = $item['old_key'];
		}
		if (!empty($item['new_key'])) {
			$handled_meta_keys[] = $item['new_key'];
		}
	}

	foreach ($results['insert'] as $item) {
		if (!empty($item['value'])) {
			$handled_field_keys[] = $item['value'];
		}
		if (!empty($item['key'])) {
			$handled_meta_keys[] = $item['key'];
		}
	}

	$handled_field_keys = array_unique($handled_field_keys);
	$handled_meta_keys = array_unique($handled_meta_keys);

	spr_capture_drop_keys(
		$results,
		$before_snapshot['structure']['fields'] ?? [],
		$after_snapshot['structure']['fields'] ?? [],
		$handled_field_keys,
		$handled_meta_keys
	);

	return [
		'from'    => $snapshot_before_key,
		'to'      => $snapshot_after_key,
		'results' => $results
	];
}


// ======================================= //
//         WRITE ROLLBACK SQL HELPER       //
// ======================================= //

/**
 * Writes a reverse SQL statement to a rollback .sql file for a given migration action.
 * Currently handles 'update', 'insert', and 'drop' actions.
 *
 * @param array       $rollback_data The structured rollback data with 'updates', 'inserts', 'drops'.
 * @param string      $blockid       The block ID (slug) used for file naming.
 * @param int|null    $post_id       The post ID used for file naming (optional).
 * @param string|null $from_snapshot The snapshot key we're rolling back from (i.e., the new one).
 * @param string|null $to_snapshot   The snapshot key we're restoring to (i.e., the old one).
 * @return void
 */

function spr_create_rollback_sql($rollback_data, $blockid, $post_id = null, $from_snapshot = null, $to_snapshot = null) {

	// Determine file path based on block ID, post ID, and local timestamp
	$dir = WP_CONTENT_DIR . '/spr-block-migrations';
	if (!file_exists($dir)) {
		mkdir($dir, 0755, true);
	}
	$timestamp = current_time('Ymd_His');
	$filepath = is_null($post_id)
		? "$dir/{$blockid}_rollback_{$timestamp}.sql"
		: "$dir/{$blockid}_rollback_{$post_id}_{$timestamp}.sql";

	// Use static to persist path across calls in same request
	static $resolved_path = null;
	if ($resolved_path === null) {
		$resolved_path = $filepath;
		$header = "-- ===================================================\n";
		$header .= "--    ROLLBACK SCRIPT FOR BLOCK: $blockid\n";
		$header .= "--    Post ID: " . ($post_id ?? 'All') . "\n";
		$header .= "--    Generated: " . current_time('Y-m-d H:i:s') . "\n";
		if ($from_snapshot && $to_snapshot) {
			$header .= "--    From snapshot: $to_snapshot\n";
			$header .= "--    Back to snapshot: $from_snapshot\n";
		}
		$header .= "-- ===================================================\n\n";
		$header .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
		$header .= "START TRANSACTION;\n";
		$header .= "SET time_zone = \"+00:00\";\n\n";
		$header .= "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n";
		$header .= "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n";
		$header .= "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n";
		$header .= "/*!40101 SET NAMES utf8mb4 */;\n\n";
		file_put_contents($resolved_path, $header);
	}

	global $wpdb;
	$table = $wpdb->prefix . 'postmeta';

	// WRITE UPDATE ROLLBACKS
	if (!empty($rollback_data['updates'])) {
		static $wrote_update_header = false;
		if (!$wrote_update_header) {
			$section = "-- ---------------------------------------------------\n";
			$section .= "-- ROLLBACK: RESTORE ORIGINAL VALUES FROM UPDATE\n";
			$section .= "-- ---------------------------------------------------\n\n";
			file_put_contents($resolved_path, $section, FILE_APPEND);
			$wrote_update_header = true;
		}

		foreach ($rollback_data['updates'] as $data) {
			$escaped_key   = str_replace("'", "''", $data['old_key']);
			$escaped_value = is_null($data['old_value']) ? 'NULL' : "'" . str_replace("'", "''", $data['old_value']) . "'";
			$escaped_id    = (int) $data['row_id'];

			$sql = "UPDATE {$table} SET meta_key = '$escaped_key', meta_value = $escaped_value WHERE meta_id = $escaped_id;\n";
			file_put_contents($resolved_path, $sql, FILE_APPEND);
		}
	}

	// WRITE INSERT ROLLBACKS
	if (!empty($rollback_data['inserts'])) {
		static $wrote_insert_header = false;
		if (!$wrote_insert_header) {
			$section = "\n-- ---------------------------------------------------\n";
			$section .= "-- ROLLBACK: REMOVE INSERTED META KEYS\n";
			$section .= "-- ---------------------------------------------------\n\n";
			file_put_contents($resolved_path, $section, FILE_APPEND);
			$wrote_insert_header = true;
		}

		foreach ($rollback_data['inserts'] as $data) {
			$post_id = (int) $data['post_id'];
			$escaped_key = str_replace("'", "''", $data['key']);

			$sql = "DELETE FROM {$table} WHERE post_id = $post_id AND meta_key = '$escaped_key' LIMIT 1;\n";
			file_put_contents($resolved_path, $sql, FILE_APPEND);
		}
	}

	// WRITE DROP ROLLBACKS
	if (!empty($rollback_data['drops'])) {
		static $wrote_drop_header = false;
		if (!$wrote_drop_header) {
			$section = "\n-- ---------------------------------------------------\n";
			$section .= "-- ROLLBACK: RESTORE DROPPED META KEYS\n";
			$section .= "-- ---------------------------------------------------\n\n";
			file_put_contents($resolved_path, $section, FILE_APPEND);
			$wrote_drop_header = true;
		}

		foreach ($rollback_data['drops'] as $data) {
			$post_id = (int) $data['post_id'];
			$escaped_key = str_replace("'", "''", $data['key']);
			$escaped_value = is_null($data['value']) ? 'NULL' : "'" . str_replace("'", "''", $data['value']) . "'";
			$meta_id = isset($data['row_id']) ? (int) $data['row_id'] : 'NULL';

			$sql = "INSERT INTO {$table} (meta_id, post_id, meta_key, meta_value) VALUES ($meta_id, $post_id, '$escaped_key', $escaped_value);\n";
			file_put_contents($resolved_path, $sql, FILE_APPEND);
		}
	}

	// Append footer to close the SQL file
	$footer = "\nCOMMIT;\n\n";
	$footer .= "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n";
	$footer .= "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n";
	$footer .= "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n";
	file_put_contents($resolved_path, $footer, FILE_APPEND);
} 


// ======================================= //
//         RUN BLOCK MIGRATIONS            //
// ======================================= //

/**
 * Executes field migrations for a specific ACF block across one or more posts.
 * Compares the latest stored snapshot to the updated structure in /partials/test/,
 * generates a migration map, and applies key/value changes to postmeta as needed.
 * @param string   $blockid  Slug of the ACF block (e.g., 'block_text').
 * @param int|null $post_id  Optional post ID to limit the migration scope.
 * @return array   Summary of the migration attempt, including logs and updated fields.
 */

function spr_run_block_migrations($blockid, $post_id = null) {

	$message = "running migration for " . $blockid . "...";
	spr_write_to_debug_log(__FILE__, __LINE__, $message, false);

	$option_name = 'spr_snapshot_' . $blockid;
	$snapshots = get_option($option_name);

	if (!is_array($snapshots) || count($snapshots) < 2) {
		return '<p>did not find 2 snapshots to work with!</p>'; // Need at least 2 snapshots to define a migration
	}

	$snapshots = array_reverse($snapshots, true);
	$snapshots = array_slice($snapshots, 0, 2, true);
	$snapshots = array_reverse($snapshots, true);

	// Grab keys to check migration state
	$snapshot_keys = array_keys($snapshots);
	$after_snapshot = $snapshots[end($snapshot_keys)];

	if (!isset($after_snapshot['migration']) || isset($after_snapshot['migration']['from'], $after_snapshot['migration']['to'])) {
		return '<p>Migration already completed or not configured properly!</p>'; // Migration complete or no migration config
	}

	// Perform the migration passing the trimmed snapshots array
	$plan = spr_build_migration_plan($blockid, $post_id, $snapshots);

	// run migration with plan
	$migration_result = spr_migrate_block_data($plan, $blockid, $post_id);

	return [
		'plan' => $plan,
		'migration' => $migration_result
	];

}


// ======================================= //
//          EXECUTE BLOCK MIGRATIONS       //
// ======================================= //

/**
 * Executes the database migrations for an ACF block based on the provided migration plan.
 * Handles updating existing meta keys, inserting new keys, and dropping obsolete keys.
 *
 * @param array    $plan The migration plan array containing 'update', 'insert', and 'drop' instructions.
 * @param int|null $post_id Post ID to limit the migration scope (null to run for all relevant posts).
 * @return array   Summary of actions performed including counts of updates, inserts, and drops.
 */

function spr_migrate_block_data($plan, $blockid, $post_id = null) {

	// if this is true, no actual database operations take place. 
	// We simply log what would have.
	$dry_run = false;

	global $wpdb;

	$table = $wpdb->postmeta;
	$updates_done = 0;
	$inserts_done = 0;
	$drops_done = 0;
	$inserts = [];

	$log = [
		'updates' => [],
		'inserts' => [],
		'drops'   => []
	];

	$rollback_data = [
		'updates' => [],
		'inserts' => [],
		'drops' => []
	];

	$migration_plan = $plan['results'];
	$snapshot_before_key = $plan['from'];
	$snapshot_after_key = $plan['to'];

	// UPDATES
	foreach ($migration_plan['update'] as $update) {

		$old_key = $update['old_key'];
		$new_key = $update['new_key'];

		// Build LIKE query to find matching rows with dynamic prefixes
		$query = "SELECT meta_id, meta_key, meta_value, post_id FROM $table WHERE meta_key LIKE %s";
		$params = ['%' . $wpdb->esc_like($old_key)];

		if ($post_id !== null) {
			$query .= " AND post_id = %d";
			$params[] = $post_id;
		}

		// $message = "about to call spr_log_wpdb_prepare()";
		// spr_write_to_debug_log(__FILE__, __LINE__, $message, false);
		$old_rows = $wpdb->get_results(spr_log_wpdb_prepare($query, ...$params));
		array_pop($update['path']);

		if (!$old_rows) {
			continue; // No data to update
		}

		foreach ($old_rows as $row) {

			if (!str_ends_with($row->meta_key, $old_key)) {
				continue; // Only process exact suffix matches
			}

			$prefix = substr($row->meta_key, 0, -strlen($old_key));
			$full_new_key = $prefix . $new_key;

			// Leave value alone if there's not a leading underscore
			if (substr($row->meta_key, 0, 1) === '_') {
				$new_value = $update['new_value'];
			} else {
				$new_value = $row->meta_value;
	  			if ($row->meta_value !== null) {
					$accum = '';
	  				foreach ($update['path'] as $s => $segment) {
						if ($accum === '') {
							$accum = $segment;
						} else {
							$accum .= '_' . $segment;
						}
			  			$inserts[] = Array(
		  					'post_id' => $row->post_id,
		  					'key' => $prefix . '_' . $accum,
		  					'value' => ''
		  				);
						if ($s > 0) {
							$cleankey = '_' . $accum;
							$matching_insert = array_filter($migration_plan['insert'], function ($item) use ($cleankey) {
								return $item['key'] === $cleankey;
							});
							$matching_insert = array_values($matching_insert); // reindex
				  			$inserts[] = Array(
			  					'post_id' => $row->post_id,
			  					'key' => '_' . $prefix . '_' . $accum,
			  					'value' => $matching_insert[0]['value']
			  				);
						}
	  				}
	  			}
			}

			if ($dry_run) {
				$log['updates'][] = [
					'post_id'     => $row->post_id,
					'old_key'     => $row->meta_key,
					'new_key'     => $full_new_key,
					'old_value'   => $row->meta_value,
					'new_value'   => $new_value,
					'row_id'      => $row->meta_id
				];
			} else {
				$wpdb->update(
					$table,
					[
						'meta_key'   => $full_new_key,
						'meta_value' => $new_value,
					],
					[ 'meta_id' => $row->meta_id ],
					[ '%s', '%s' ],
					[ '%d' ]
				);
				$updates_done++;
			}

			$rollback_data['updates'][] = [
				'post_id'     => $row->post_id,
				'old_key'     => $row->meta_key,
				'new_key'     => $full_new_key,
				'old_value'   => $row->meta_value,
				'new_value'   => $new_value,
				'row_id'      => $row->meta_id
			];

		}
	}

	// INSERTS
	$valid_insert_keys = [];
	foreach ($migration_plan['insert'] as $entry) {
		if (isset($entry['key'])) {
			$valid_insert_keys[] = $entry['key'];
		}
	}
	foreach ($inserts as $insert) {
		$cleankey = preg_replace('/_?content_blocks_\d+_/', '', $insert['key']);
		if (!in_array($cleankey, $valid_insert_keys, true)) {
			continue;
		}
		if ($dry_run) {
			$log['inserts'][] = $insert;
		} else {
			$wpdb->insert(
				$table,
				[
					'post_id'    => $insert['post_id'],
					'meta_key'   => $insert['key'],
					'meta_value' => $insert['value']
				],
				['%d', '%s', '%s']
			);
			$inserts_done++;
		}

		$rollback_data['inserts'][] = [
			'post_id' => $insert['post_id'],
			'key'     => $insert['key']
		];

	}

	// DROPS
	if (!empty($migration_plan['drop'])) {
		foreach ($migration_plan['drop'] as $drop_key) {

			if (empty($drop_key) || trim($drop_key) === '' || $drop_key === '%' || $drop_key === '_') {
				continue; // Unsafe or meaningless key — skip
			}

			$query = "SELECT meta_id, meta_key, post_id, meta_value FROM $table WHERE meta_key LIKE %s";
			$params = ['%' . $wpdb->esc_like($drop_key)];

			if ($post_id !== null) {
				$query .= " AND post_id = %d";
				$params[] = $post_id;
			}

			$rows = $wpdb->get_results(spr_log_wpdb_prepare($query, ...$params));

			foreach ($rows as $row) {
				if (!str_ends_with($row->meta_key, $drop_key)) {
					continue;
				}

				if ($dry_run) {
					$log['drops'][] = [
						'post_id'   => $row->post_id,
						'key'       => $row->meta_key,
						'value'     => $row->meta_value,
						'row_id'    => $row->meta_id
					];
				} else {
					$wpdb->delete(
						$table,
						[ 'meta_id' => $row->meta_id ],
						[ '%d' ]
					);
					$drops_done++;
				}

				$rollback_data['drops'][] = [
					'post_id' => $row->post_id,
					'key'     => $row->meta_key,
					'value'   => $row->meta_value,
					'row_id'    => $row->meta_id
				];

			}
		}
	}

	spr_create_rollback_sql($rollback_data, $blockid, $post_id, $snapshot_before_key, $snapshot_after_key);

	return [
		'updates' => $updates_done,
		'inserts' => $inserts_done,
		'drops'   => $drops_done,
		'log'     => $dry_run ? $log : null
	];

}





// ======================================= //
//            TESTING UTILITIES            //
// ======================================= //

if (isset($_GET['m']) && $_GET['m'] === 'test') {

	if (!current_user_can('manage_options')) {
		wp_die('Permission denied.');
	}

	echo('<pre style="margin-top: 30px; margin-bottom: 30px;">');
	echo('<p>manage option:');
	echo('&nbsp;');
	echo('<a href="?m=test&o=snapshots">view snapshots</a>');
	echo('&nbsp;|&nbsp;');
	echo('<a href="?m=test&o=map">view migration</a>');
	echo('&nbsp;|&nbsp;');
	echo('<a href="?m=test&a=rewind-option">rewind option</a>');
	echo('&nbsp;|&nbsp;');
	echo('<a href="?m=test&a=rebuild-option">rebuild option</a>');
	echo('</p>');
	echo('<p>migration:');
	echo('&nbsp;');
	echo('<a href="?m=test&a=migrate">migrate</a>');
	echo('&nbsp;|&nbsp;');
	echo('<a href="?m=test&a=rollback">test rollback</a>');
	echo('</pre>');

	if (isset($_GET['a']) && $_GET['a'] === 'rewind-option') {
		$snapshots = get_option('spr_snapshot_block_text');
		foreach ($snapshots as $s => $snapshot) {
			if ($s !== '2025-06-16') {
				unset($snapshots[$s]);
			}
		}
		update_option('spr_snapshot_block_text', $snapshots);
		echo('<pre>'); print_r('option reset!'); echo('</pre>');
	}

	if (isset($_GET['a']) && $_GET['a'] === 'rebuild-option') {
		delete_option('spr_snapshot_block_text');
		spr_register_block_changes('block_text', []);
		$snapshots = get_option('spr_snapshot_block_text');
		$original_key = array_key_first($snapshots);
		$snapshots['2025-06-16'] = $snapshots[$original_key];
		unset($snapshots[$original_key]);
		update_option('spr_snapshot_block_text', $snapshots);
		echo('<pre>'); print_r('option rebuilt! <a href="?m=test&o=snapshots">view it</a>'); echo('</pre>');
	}

	if (isset($_GET['o'])) {
		spr_register_block_changes('block_text', []);
	}

	if (isset($_GET['a']) && $_GET['a'] === 'migrate') {

		if (!isset($_GET['post_id'])) { // Basic form UI

			echo('<pre style="margin-top: 20px;">');
			echo('migrate post-id... (block_text only)</br>');
			echo('<form style="margin-top: 6px;" method="GET">');
			echo('<input type="hidden" name="m" value="test">');
			echo('<input type="hidden" name="a" value="migrate-run">');
			echo('<input type="number" name="post_id" value="957">&nbsp;');
			echo('<input type="submit">');
			echo('</form>');
			echo('</pre>');

			exit;

		}

	}

	if (isset($_GET['a']) && $_GET['a'] === 'migrate-run') {

		// Run the migration
		echo('<pre>'); echo('Running migration for block_text');
		if (isset($_GET['post_id']) && strlen($_GET['post_id']) > 0) {
			$post_id = intval($_GET['post_id']);
		 	echo(' on post #') . $post_id;
		}
		echo('!</pre>');

		$result = spr_register_block_changes('block_text', [], $post_id);
		echo('<pre>'); print_r($result); echo('</pre>');

		exit;

	}

}

if (!defined('ABSPATH') && !function_exists('spr_register_block_changes')) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
}


?>