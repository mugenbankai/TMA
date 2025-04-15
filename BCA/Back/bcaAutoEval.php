<?php

include('header.php');

?>

		<section>
			<h2 id="courses">Auto évaluation</h2>
			<div id="autoEval">
				<i class="fa fa-kiwi-bird rater" data-value="1"></i> 
				<i class="fa fa-graduation-cap rater" data-value="2"></i> 
				<i class="fa fa-handshake rater" data-value="3"></i> 
				<i class="fa fa-hand-holding rater" data-value="4"></i> 
				<i class="fa fa-star rater" data-value="5"></i>
				<span id="nameOfRank"></span>
			</div>
		</section>
	</div>

<script type="text/javascript">
	window.onload = () => {
		var raters = document.querySelectorAll('.rater');

		for(rater of raters){
			rater.addEventListener('mouseover', function() {
				let numberOfSelectedRank = this.dataset.value;
				document.getElementById('nameOfRank').innerHTML = numberOfSelectedRank;
			})
		}
	}
</script>
</body>
</html>