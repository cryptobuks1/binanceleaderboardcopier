# binanceleaderboardcopier
<h1>Binance Leaderboard Copier</h1><br>
<h2>Automated cryptocurrency trading bot</h2><br>
Using this bot you can copy trades from the highest performing traders from across Binance Futures.<br>
This bot can be highly profitable in case you choose the correct trader to follow (I coded it and I am  already using it but I have a very tight capital)<br>
<h1>What is Binance Futures Leaderboard?</h1><br>
Binance Futures leaderboard is the showcase of the best performing traders from across binance futures.<br>
<a href = 'https://www.binance.com/en/futures-activity/leaderboard'>https://www.binance.com/en/futures-activity/leaderboard</a><br>
Some Traders are sharing their positions so the role of this bot is to mirror a choosen trader.<br>

<h1>How to use the robot?</h1><br>
I coded this robot using pure PHP,MYSQL and PHP CCXT library for binance<br>
I also used an attractive and free bootstrap template <a href='https://startbootstrap.com/theme/sb-admin-2'>SB Admin 2 </a><br>
This bot can run 24/7 on a windows vps<br>

<h1>Setting Up the bot on a windows VPS</h1><br>
Install <b>XAMPP</b> on the VPS <a href='https://www.apachefriends.org/download.html'>XAMPP for windows </a><br>
Then Install <a href='https://downloads.mysql.com/archives/query/'>MYSQL Query Browser</a>
<br>Then Turn on Apache and mysql Modules.<br>
Open MYSQL query browser <br>
Server host : localhost<br>
username : root<br>
password: blank<br>
Create a database called "binancefutures"<br>
import the db file database.sql into binancefutures schema.
Under c >> XAMPP >> HTDOCS paste the entire files.<br>

<h1>Running The bot in chrome</h1><br>
Bot will not work without the insert of this command in the run command:<br>
<b>chrome.exe  --disable-site-isolation-trials --disable-web-security --user-data-dir="C:\temp"</b>


