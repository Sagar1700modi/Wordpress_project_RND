<?php
$fields = get_fields();

$title   = $fields['best_casino_title'] ?? '';

$main_heading = $fields['toc_main_heading'];
$headings = $fields['toc_headings'];

$class_string = $fields['toc_class_names'];
$exclude_classes = [];

if ($class_string) {
    $exclude_classes = array_filter(array_map('trim', explode(',', $class_string)));
}

if (empty($headings)) {
    return;
}
?>
<span class="toc-heading"><?php echo $main_heading;?></span>
<div class="accordion question-accordion toc-questions"
     data-toc-headings="<?php echo esc_attr(implode(',', $headings)); ?>"
     data-toc-exclude="<?php echo esc_attr(implode(',', $exclude_classes)); ?>">
</div>
