<!DOCTYPE html>
<html lang="en">

	<head>
		<title>Salesforce Test</title>
		<script type="text/javascript" src="libs/jquery.js"></script>
		<link rel="stylesheet" type="text/css" href="libs/site.css" />
	</head>
	<body>
		<h1>Tweeter feed reader @salesforce</h1>
		<div class="search_tweets">
			<input type="text" id="search_tweet" placeholder="Search Tweets" size="35" autocomplete="off" />
		</div>
		<div id="tweets">
			
		</div>
	</body>

	<script type="text/javascript">
		$(document).ready(function() {

			var twitter = function() {
				$.ajax({
					url: 'server.php',
					dataType: 'json',
					success: function(data) {
						var html = "";
						$.each(data, function(index, value) {
							html += '<div class="tweet">';
							html += '<a class="left" href="https://twitter.com/'+value.screen_name+'">';
							html += '<img src="'+value.profile_image+'" class="profile_pic" alt="64x64" /></a>';
							html += '<div class="tweet_content"><h4 class="tweet_heading">'+value.user_name;
							html += ' <a href="https://twitter.com/'+value.screen_name+'">@'+value.screen_name+'</a>';
							html += ' <span class="retweet" title="Retweet Count">'+value.retweet_count+'</span></h4>';
							html += '<div class="tweet_body">'+value.tweet+"</div></div></div>"
						});
						$("#tweets").html(html);
					}
				});
			}
			twitter();
			window.setInterval(twitter, 60000);


			$("#search_tweet").on("keyup paste", function() {
				var search_for = $(this).val().trim().toLowerCase();
				if(search_for === "") {
					$(".tweet").show();
					return false;
				}
				$(".tweet .tweet_body").each(function(i, v) {
					var tw_cont = $(v).text().toLowerCase();
					if(tw_cont.search(search_for) > -1) {
						$(this).closest('.tweet').show();
					}
					else {
						$(this).closest('.tweet').hide();
					}
				});
			});

		});
	</script>

</html>