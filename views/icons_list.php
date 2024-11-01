<ul>

<?php



	foreach($icons_list as $icon)

	{

		echo '<li rel="'.esc_attr($icon).'"><i class="fa fa-'.esc_attr($icon).'"></i>'.esc_attr($icon).'</li>';

	}



?>

</ul>