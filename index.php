<?php

// Include the main functions
require_once( 'inc/functions.php' );

$maker = new KHPixelMaker( 'data/base-maker-data.json' );


echo '<!DOCTYPE html>';
echo '<html lang="en">';

	echo '<head>';

		echo '<meta charset="utf-8">';
		echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
		echo '<title>Create a pixel art character!</title>';
		echo '<link rel="stylesheet" href="assets/css/style.css">';

	echo '</head>';

	echo '<body>';

		echo '<main>';

			if ( isset( $_GET[ 'save' ] ) ) {

				echo '<section class="sc gr" id="save">';

					$choices = $maker->get_item_choices();
					$image = $maker->create_image( $choices );

					if ( $image ) {

						echo '<div>';

							echo '<h1 class="t-u t-s" id="h-save">Your Pixel Art Character</h1>';
							echo '<p>Save your pixel art character below.</p>';
							echo '<img alt="Generated Character Image" src="' . $image . '">';
							echo '<p class="o-b"><a class="btn t-u t-tn" href="' . $image . '" download>Download</a> <a class="btn t-u t-tn" href="index.php#create">Start Again</a></p>';

						echo '</div>';

					} else {

						echo '<h1>Oops!</h1>';
						echo '<p>Something went wrong! <a href="/">Please try again.</a></p>';

					}

				echo '</section>';

			} else {

				echo '<section id="splash" class="gr">';

					echo '<div class="deco">';

						echo '<h1 class="t-u t-s"><span>Make a Pixel Art</span> Character</h1>';
						echo '<p class="o-b"><a class="btn t-u t-tn" href="#create">Start Designing</a></p>';

					echo '</div>';

				echo '</section>';

				echo '<section class="sc gr" id="create">';

					echo '<div class="wrap">';

						echo $maker->render_form();

					echo '</div>';

				echo '</section>';

			}

		echo '</main>';

		echo '<footer>Created with ♥ by <a href="https://twitter.com/KawaiiHannahArt" target="_blank">Hannah Malcolm</a> - View this project on <a href="https://github.com/KawaiiHannah/pixel-character" target="_blank">GitHub</a></footer>';

	echo '</body>';

echo '</html>';