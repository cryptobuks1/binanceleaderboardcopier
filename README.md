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
<br>This Command will disable the cross origin issue because a popup is used to collect the trades.<br>
Enter this url : http://localhost/cryptofutures/admin/ <br>
username : <b>admin</b><br>
pass: <b>hello_world_01</b><br>

There 2 modes for the bots : admin mode and vps mode.<br>
First you have to configure some params in the admin mode.
3 things need to be configured : Tradable pairs,Create API and global parameters

<b>1.Tradable Pairs</b><br>
I have already added 118 coins (The full tradable coins under the futures section)
In case binance listed new coins for the futures section
You can add the coin abbr : ex ETH,BTC,XRP,ADA (Make sure its uppercase)
Coin Name : The coin full name
Coin Digits : The decimal place
and you can also upload coin image ( optional)


<b>2.Create API</b><br>
Creating API KEY and SECRET KEY
Log into your Binance account and go to the account settings -> API Management page where you can create a new API key.
make sure you enable 'Enable Trading', and 'Enable Futures' are enabled.
Once your keys are created, make a note of the API Key and Secret Key.
Transfer some USDT balance from spot to futures.

Click on add API and KEY :
client email (enter your email or username here)
client pass(enter your password here)
binance api key : paste the api key here
binance secret key : paste the secret key here
binance leverage : enter the leverage here ( I recommend 2)
Binance lot amout per trade : input here your capital divided per 13
keep the last field 1 (this mean enable futures yes (1) no (0))


<b>3.Global Parameters (The most import section)</b><br>
param_maxtrades : set the max number of trades the robot take ( I recommend 10)<br><br>
param_copyingsrc : set the id of the trader you want to copy trades<br>
https://www.binance.com/en/futures-activity/leaderboard<br>
Then click USD-M Futures<br>
Check User sharing position<br>
Time interval : All (recommended)
Click on any trader you like url will look like this :<br>
https://www.binance.com/en/futures-activity/leaderboard?type=myProfile&tradeType=PERPETUAL&encryptedUid=9745A111F31F836D6D2E9F758DA3A07B
I really like this trader and I am mirroring it on my own account (100% growth in 2 months)
paste the string after "encryptedUid=" into param_copyingsrc
<br>
param_max_loss_percentage : I recommend the default I set which is 5
param_max_profit_percentage : I recommend the default I set which is 7
param_on_profit_freeze_hours : 2(default)
param_on_loss_freeze_hours: 4(default)
param_on_client_close_all_trades_freeze_hours : 2 (default)
<br>
NB: robot will freeze x hours when SL or TP are hitten <br>




