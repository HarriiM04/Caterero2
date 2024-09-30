<?php
session_start();

$_SESSION['customer_id'] = 1;

include 'adminback.php';

$admin = new adminback();

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$package = $admin->display_packageByID($id);

	if ($package) {
		// Assuming the $package contains the required fields
		$name = $package['name'];
		$category = $package['category'];
		$cuisine = $package['cuisine'];
		$price = $package['price'];
		$description = $package['description'];
		$image = $package['image'];
		$availability = $package['availability'] ? "Available" : "Not Available";
		$items = json_decode($package['items'], true);  // Convert JSON string to an associative array
	} else {
		echo "No package found!";
		exit;
	}
} else {
	echo "Package ID not specified!";
	exit;
}


if (isset($_POST['add_to_cart'])) {
	$package_id = $_POST['package_id'];
	$custpackage_id = 0; // Assuming 0 for a predefined package, as no customization is involved

	$result = $admin->addToCart($_SESSION['customer_id'], $package_id );

	if ($result) {
		echo '<script>alert("Package added to cart successfully!");</script>';
	} else {
		echo '<script>alert("Failed to add package to cart.");</script>';
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Home</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php include 'head_style.php'  ?>
</head>

<body class="animsition">

	<?php include 'head.php'  ?>



	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="index.php" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>



			<span class="stext-109 cl4">
				<?php echo $package['name']; ?>
			</span>
		</div>
	</div>


	<!-- Product Detail -->
	<section class="sec-product-detail bg0 p-t-65 p-b-60">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-lg-7 p-b-30">
					<div class="p-l-25 p-r-30 p-lr-0-lg">
						<div class="wrap-slick3 flex-sb flex-w">
							<div class="wrap-slick3-dots"></div>
							<div class="wrap-slick3-arrows flex-sb-m flex-w"></div>

							<div class="slick3 gallery-lb">
								<div class="item-slick3" data-thumb="images/<?php echo $package['image']; ?>">
									<div class="wrap-pic-w pos-relative">
										<img src="images/<?php echo $package['image']; ?>" style="width:100%;">

										<a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="images/product-detail-01.jpg">
											<i class="fa fa-expand"></i>
										</a>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-5 p-b-30">
					<div class="p-r-50 p-t-5 p-lr-0-lg">
						<h4 class="mtext-105 cl2 js-name-detail p-b-14">
							<?php echo $package['name']; ?>
						</h4>

						<span class="mtext-106 cl2">
							$<?php echo $package['price']; ?>
						</span>

						<p class="stext-102 cl3 p-t-23">
							<?php echo $package['description']; ?>
						</p>


						<!-- Fetch and display categorized items -->
						<div class="p-t-33">
							<?php
							// Decode the JSON object where keys are category IDs and values are arrays of item IDs
							$items = json_decode($package['items'], true);

							if (json_last_error() !== JSON_ERROR_NONE) {
								echo 'JSON Error: ' . json_last_error_msg();
							} elseif (is_array($items) && !empty($items)) {
								// Fetch all categories to map category IDs to category names
								$categories = $admin->getCategoriess();  // Method to fetch all categories
								$categoryMap = [];
								while ($category = mysqli_fetch_assoc($categories)) {
									$categoryMap[$category['id']] = $category['name'];  // Create a map of category ID => category name
								}

								// Initialize the output array
								$output = [];

								// Loop through each category in the JSON object
								foreach ($items as $categoryId => $itemIds) {
									// Ensure category exists in our map
									if (isset($categoryMap[$categoryId])) {
										// Fetch items by their IDs for this category
										$ids = implode(',', array_map('intval', $itemIds));  // Sanitize and prepare the ID list
										$result = $admin->getItemsByIds($ids);  // Fetch the item names for these IDs

										// Collect the item names
										$itemNames = [];
										while ($item = mysqli_fetch_assoc($result)) {
											$itemNames[] = $item['Name'];
										}

										// Only display if there are items found
										if (!empty($itemNames)) {
											// Add the category and its items to the output
											$output[] = "<b>Category: " . $categoryMap[$categoryId] . "</b><ul><li>" . implode('</li><li>', $itemNames) . "</li></ul>";
										}
									}
								}

								// Output the formatted items grouped by category
								if (!empty($output)) {
									echo implode('<br>', $output);  // Separate each category's output with a line break
								} else {
									echo "No items found for this package.";
								}
							} else {
								echo "Invalid JSON data or empty items.";
							}
							?>
						</div>

						<!-- Availability -->
						<div class="p-t-33">
							<p><strong>Availability:</strong> <?php echo $package['availability']; ?></p>
						</div>

						<!-- Add to cart button -->
						<form method="POST" action="">
							<input type="hidden" name="package_id" value="<?php echo $package['id']; ?>">
							<button type="submit" name="add_to_cart" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail">
								Add to Cart
							</button>
						</form>
					</div>
				</div>
				<!--  -->
				<div class="flex-w flex-m p-l-100 p-t-40 respon7">
					<div class="flex-m bor9 p-r-10 m-r-11">
						<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100" data-tooltip="Add to Wishlist">
							<i class="zmdi zmdi-favorite"></i>
						</a>
					</div>

					<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Facebook">
						<i class="fa fa-facebook"></i>
					</a>

					<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Twitter">
						<i class="fa fa-twitter"></i>
					</a>

					<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Google Plus">
						<i class="fa fa-google-plus"></i>
					</a>
				</div>
			</div>
		</div>
		</div>

		<div class="bor10 m-t-50 p-t-43 p-b-40">
			<!-- Tab01 -->
			<div class="tab01">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item p-b-10">
						<a class="nav-link active" data-toggle="tab" href="#description" role="tab">Description</a>
					</li>

					<li class="nav-item p-b-10">
						<a class="nav-link" data-toggle="tab" href="#information" role="tab">Additional information</a>
					</li>

					<li class="nav-item p-b-10">
						<a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Reviews (1)</a>
					</li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content p-t-43">
					<!-- - -->
					<div class="tab-pane fade show active" id="description" role="tabpanel">
						<div class="how-pos2 p-lr-15-md">
							<p class="stext-102 cl6">
								Aenean sit amet gravida nisi. Nam fermentum est felis, quis feugiat nunc fringilla sit amet. Ut in blandit ipsum. Quisque luctus dui at ante aliquet, in hendrerit lectus interdum. Morbi elementum sapien rhoncus pretium maximus. Nulla lectus enim, cursus et elementum sed, sodales vitae eros. Ut ex quam, porta consequat interdum in, faucibus eu velit. Quisque rhoncus ex ac libero varius molestie. Aenean tempor sit amet orci nec iaculis. Cras sit amet nulla libero. Curabitur dignissim, nunc nec laoreet consequat, purus nunc porta lacus, vel efficitur tellus augue in ipsum. Cras in arcu sed metus rutrum iaculis. Nulla non tempor erat. Duis in egestas nunc.
							</p>
						</div>
					</div>

					<!-- - -->
					<div class="tab-pane fade" id="information" role="tabpanel">
						<div class="row">
							<div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
								<ul class="p-lr-28 p-lr-15-sm">
									<li class="flex-w flex-t p-b-7">
										<span class="stext-102 cl3 size-205">
											Weight
										</span>

										<span class="stext-102 cl6 size-206">
											0.79 kg
										</span>
									</li>

									<li class="flex-w flex-t p-b-7">
										<span class="stext-102 cl3 size-205">
											Dimensions
										</span>

										<span class="stext-102 cl6 size-206">
											110 x 33 x 100 cm
										</span>
									</li>

									<li class="flex-w flex-t p-b-7">
										<span class="stext-102 cl3 size-205">
											Materials
										</span>

										<span class="stext-102 cl6 size-206">
											60% cotton
										</span>
									</li>

									<li class="flex-w flex-t p-b-7">
										<span class="stext-102 cl3 size-205">
											Color
										</span>

										<span class="stext-102 cl6 size-206">
											Black, Blue, Grey, Green, Red, White
										</span>
									</li>

									<li class="flex-w flex-t p-b-7">
										<span class="stext-102 cl3 size-205">
											Size
										</span>

										<span class="stext-102 cl6 size-206">
											XL, L, M, S
										</span>
									</li>
								</ul>
							</div>
						</div>
					</div>

					<!-- - -->
					<div class="tab-pane fade" id="reviews" role="tabpanel">
						<div class="row">
							<div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
								<div class="p-b-30 m-lr-15-sm">
									<!-- Review -->
									<div class="flex-w flex-t p-b-68">
										<div class="wrap-pic-s size-109 bor0 of-hidden m-r-18 m-t-6">
											<img src="images/avatar-01.jpg" alt="AVATAR">
										</div>

										<div class="size-207">
											<div class="flex-w flex-sb-m p-b-17">
												<span class="mtext-107 cl2 p-r-20">
													Ariana Grande
												</span>

												<span class="fs-18 cl11">
													<i class="zmdi zmdi-star"></i>
													<i class="zmdi zmdi-star"></i>
													<i class="zmdi zmdi-star"></i>
													<i class="zmdi zmdi-star"></i>
													<i class="zmdi zmdi-star-half"></i>
												</span>
											</div>

											<p class="stext-102 cl6">
												Quod autem in homine praestantissimum atque optimum est, id deseruit. Apud ceteros autem philosophos
											</p>
										</div>
									</div>

									<!-- Add review -->
									<form class="w-full">
										<h5 class="mtext-108 cl2 p-b-7">
											Add a review
										</h5>

										<p class="stext-102 cl6">
											Your email address will not be published. Required fields are marked *
										</p>

										<div class="flex-w flex-m p-t-50 p-b-23">
											<span class="stext-102 cl3 m-r-16">
												Your Rating
											</span>

											<span class="wrap-rating fs-18 cl11 pointer">
												<i class="item-rating pointer zmdi zmdi-star-outline"></i>
												<i class="item-rating pointer zmdi zmdi-star-outline"></i>
												<i class="item-rating pointer zmdi zmdi-star-outline"></i>
												<i class="item-rating pointer zmdi zmdi-star-outline"></i>
												<i class="item-rating pointer zmdi zmdi-star-outline"></i>
												<input class="dis-none" type="number" name="rating">
											</span>
										</div>

										<div class="row p-b-25">
											<div class="col-12 p-b-5">
												<label class="stext-102 cl3" for="review">Your review</label>
												<textarea class="size-110 bor8 stext-102 cl2 p-lr-20 p-tb-10" id="review" name="review"></textarea>
											</div>

											<div class="col-sm-6 p-b-5">
												<label class="stext-102 cl3" for="name">Name</label>
												<input class="size-111 bor8 stext-102 cl2 p-lr-20" id="name" type="text" name="name">
											</div>

											<div class="col-sm-6 p-b-5">
												<label class="stext-102 cl3" for="email">Email</label>
												<input class="size-111 bor8 stext-102 cl2 p-lr-20" id="email" type="text" name="email">
											</div>
										</div>

										<button class="flex-c-m stext-101 cl0 size-112 bg7 bor11 hov-btn3 p-lr-15 trans-04 m-b-10">
											Submit
										</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>

	</section>



	<?php
	include 'footer.php'
	?>

	<?php
	include 'footer_script.php'
	?>
</body>

</html>