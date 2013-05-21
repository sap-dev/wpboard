<?php
	/*
		This file is for manually converting .tpl-files to .php.
		It gets gemoved in the final release.
	*/

	$file = 'styles/standard/' . htmlspecialchars($_GET['f']);

	if (!empty($_GET['f']) && file_exists($file)) {
		$c = file_get_contents($file);

		$cA = array(
			'^<!-- INCLUDE ([a-zA-Z0-9]+)\.tpl -->^',

			'^\{([a-zA-Z0-9_.\-]+)\.([a-zA-Z0-9_.\-]+)\}^',
			'^\{([a-zA-Z0-9_.\-]+)\}^',

			'^<!-- BEGIN ([a-zA-Z0-9_\-]+) -->^',
			'^<!-- END ([a-zA-Z0-9_\-]+) -->^',

			'^<!-- IF ([a-zA-Z0-9\-_]+) -->^',
			'^<!-- IF ([a-zA-Z0-9\-_]+)\.([a-zA-Z0-9\-_]+) -->^',
			'^<!-- IF ([a-zA-Z0-9\-_]+) (==|<|>) \'?([a-zA-Z0-9\-_]+)\'? -->^',
			'^<!-- IF ([a-zA-Z0-9\-_]+)\.([a-zA-Z0-9\-_]+) (==|>|<) \'?([a-zA-Z0-9\.\-_]+)\'? -->^',

			'^<!-- IF not ([a-zA-Z0-9\-_]+) -->^i',
			'^<!-- IF not ([a-zA-Z0-9\-_]+)\.([a-zA-Z0-9\-_]+) -->^i',

			'^<!-- ELSEIF ([a-zA-Z0-9\-_]+) -->^',
			'^<!-- ELSEIF ([a-zA-Z0-9\-_]+)\.([a-zA-Z0-9\-_]+) -->^',
			'^<!-- ELSEIF ([a-zA-Z0-9\-_]+) (==|<|>) \'?([a-zA-Z0-9\-_]+)\'? -->^',
			'^<!-- ELSEIF ([a-zA-Z0-9\-_]+)\.([a-zA-Z0-9\-_]+) (==|>|<) \'?([a-zA-Z0-9\.\-_]+)\'? -->^',

			'^<!-- ELSE -->^',
			'^<!-- ENDIF -->^'
		);

		$cB = array(
			"<?php template::display('$1'); ?>",

			"<?=\$$1['$2']; ?>",
			"<?=template::getVar('$1'); ?>",

			'<?php'."\n".'if (isset(template::$blocks[\'$1\'])):'."\n".'foreach(template::$blocks[\'$1\'] as \$$1):'."\n".'?>',
			"<?php\nendforeach;\nendif;\n?>",
			
			"<?php if (template::getVar('$1')): ?>",
			"<?php if (\$$1['$2']): ?>",
			"<?php if (template::getVar('$1') $2 '$3'): ?>",
			"<?php if (\$$1['$2'] $3 '$4'): ?> ",

			"<?php if (!template::getVar('$1')): ?>",
			"<?php if (!\$$1['$2']): ?>",

			"<?php elseif (template::getVar('$1')): ?>",
			"<?php elseif (\$$1['$2']): ?>",
			"<?php elseif (template::getVar('$1') $2 '$3'): ?>",
			"<?php elseif (\$$1['$2'] $3 '$4'): ?> ",

			"<?php else: ?>",
			"<?php endif; ?>"
		);

		$c = preg_replace($cA, $cB, $c);

		$c = preg_replace('^template\:\:getVar\(\'USER_ID\'\)^i', '$user->row', $c);

		echo '<pre>'.htmlspecialchars($c).'</pre>';
	}
?>