<?php
/***************************** Acalu*/
  if ($_GET['nome']) {
     $nome = $_GET['nome'];
  }
  else
  {
	 $nome = "valor";	
  }

  if ($_GET['div_nome']) {
     $div_nome = $_GET['div_nome'];
  }
  else
  {
	 $div_nome = "div_valor";	
  }
  
  if ($_GET['acao_nome']) {
     $acao_nome = $_GET['acao_nome'];
  }
  else
  {
	 $acao_nome = "acao_valor";	
  }



/***************************** Acalu*/
  /******************************************************
   Calender 2.22
   Written by Erik Holman at 6 May 2004
   Started at 16:00 and ended at 17:00

   CHANGES by Daniele Russolillo, 18 May 2004
     + variables for CSS definition of week days
     + variables for easy translation of week days and months
     + infotitles for previous/following URLS on the calendar
     Personal homepage at http://daniele.nkoni.org  If you like
     the changes have a look at http://www.nkoni.org/ and
     pass it on to help 500 Ugandan AIDS Orphans.

   CHANGES by Erik Holman, 19 May 2004
     + variables for the language file.
     + added possibility to load language files

   CHANGES by Erik Holman, 17 June 2004
     + Added possibility to load events from MySQL database

   BUG SOLVED by Erik Holman, 24 June 2004
     - Instead of loading one event, all the events will be shown now.

   To look for from Erik Holman changes just FIND e-man within this script...
   To look for from darussol changes just FIND darussol within this script...

   ===================================
   This scripts prints  a calendar  into a table on the
   page.  You can set some variables to get a differend
   layout.

   Visit www.my-php.tk for more free PHP scripts.
  ******************************************************/

  /////////////////////////////////////////////
  //Declare some variables
  //
  $calendar_script          = "calendar2.php"; //The location of this script
  $calendar_language        = "pr";       //The extension of the calendar language file.

  $content_background_color = "#EEEEEE";   //Background color of the column
  $content_font_color       = "#000000";   //The font color
  $content_font_size        = 9;          //Font-size in pixels
  $content_font_style       = "normal";    //Set to italic or normal
  $content_font_weight      = "normal";    //Set to bold or normal

  $today_background_color   = "white";   //Background color of the column
  $today_font_color         = "blue";   //The font color
  $today_font_size          = 9;          //Font-size in pixels
  $today_font_style         = "normal";    //Set to italic or normal
  $today_font_weight        = "bold";      //Set to bold or normal

  $event_background_color   = "#DDDDDD";   //Background color of the column
  $event_background_color2  = "#EEEEEE";   //Background color of the 2nd column (event popup)
  $event_font_color         = "#000000";   //The font color
  $event_font_size          = 9;          //Font-size in pixels
  $event_font_style         = "normal";    //Set to italic or normal
  $event_font_weight        = "bold";      //Set to bold or normal
  $event_popup_width        = "250";       //Width  of the popup for the events
  $event_popup_height       = "350";       //Height of the popup for the events

  $head_background_color    = "#DDDDDD";   //Background color of the column
  $head_font_color          = "green";   //The font color
  $head_font_color_close    = "red";   //The font color
  $head_font_size           = 9;          //Font-size in pixels
  $head_font_style          = "normal";    //Set to italic or normal
  $head_font_weight         = "bold";      //Set to bold or normal

  //darussol: CSS OPTIONS FOR WEEK DAYS
  $days_head_background_color = "#DDDDDD";   //Background color of the column
  $days_head_font_color       = "gray";   //The font color
  $days_head_font_size        = 9;          //Font-size in pixels
  $days_head_font_style       = "normal";    //Set to italic or normal
  $days_head_font_weight      = "bold";      //Set to bold or normal

  $table_border             = 1;           //The border of the table
  $table_cellspacing        = 1;           //Cellspacing of the table
  $table_cellpadding        = 2;           //Cellpadding of the table
  $table_width              = '';          //Table width in pixels or %'s
  $table_height             = '';          //Table height in pixels or %'s

  $head_link_color          = "green";    //The color of the link for previous/next month

  $font_family = "Verdana";

  /* 17 June 2004 : Check readme.txt for MySQL code for the database table */

  $events_from_database     = false;        //Set to true if you want to retrieve events
  $database                 = "database";  //Name of the database within the event_table
  $server                   = "localhost"; //Name of the server
  $username                 = "username";  //MySQL username
  $password                 = "********";  //MySQL password
  $event_table              = "calendar_events"; //Name of the calendar_events
  //
  /////////////////////////////////////////////

  /////////////////////////////////////////////
  //Load the language into usable variables
  //

  //darussol: TRANSLATION (18 May 2004)
  //        : Fill in the names of the days/months in variables
  //e-man   : LOAD TRANSLATION FILE INTO VARIABLES (from darussol)(19 May 2004)
  //        : Put the days/months names from language file into a array

  $language_file  = "calendar." . $calendar_language;     //Language file into variable
  $fd             = fopen( $language_file, "r" );             //Open the language file
  $fd             = fread( $fd, filesize( $language_file ) ); //Read the opened file
  $language_array = explode( "\n" , $fd );                    //Put file info into array

  $dayname   = array_slice($language_array,0,7); //The names of the days

  $monthname = array_slice($language_array,7);   //The rest of the language file are the monthnames
  //
  /////////////////////////////////////////////


  /////////////////////////////////////////////
  //Use the date to build up the calendar. From the Query_string or the current date
  //
  if($_GET['date'])
    list($month,$year) = explode("-",$_GET['date']);
  else
  {
    $month = date("m");
    $year  = date("Y");
  }
  //
  /////////////////////////////////////////////

  $date_string = mktime(0,0,0,$month,1,$year); //The date string we need for some info... saves space ^_^

  $day_start = date("w",$date_string);  //The number of the 1st day of the week

  /////////////////////////////////////////////
  //Filter the current $_GET['date'] from the QUERY_STRING
  //
  $QUERY_STRING = ereg_replace("&date=".$month."-".$year,"",$_SERVER['QUERY_STRING']);
  //
  /////////////////////////////////////////////


  /////////////////////////////////////////////
  //Calculate the previous/next month/year
  //
  if( $month < 12 )
  {
    $next_month = $month+1;
    $next_date = $next_month."-".$year;
  }
  else
  {
    $next_year = $year+1;
    $next_date = "1-".$next_year;
    $next_month = 1;
  }
  if( $month > 1 )
  {
    $previous_month = $month-1;
    $next_month    = $month+1;
    $previous_date = $previous_month."-".$year;
  }
  else
  {
    $previous_year = $year-1;
    $previous_date = "12-".$previous_year;
    $previous_month = 12;
  }
  //
  /////////////////////////////////////////////

  // darussol: DEFINITION OF THETRANSLATED MONTH+YEAR TO BE USED IN THE TABLE AND INFO-TITLES (18 May 2004)
  // e-man   : USING THE VALUES OF THE PREVIOUS AND NEXT MONTH FOR THE TITLE DAY (19 May 2004);
  $table_caption_prev = $monthname[$previous_month-1] . " " . $year; // previous
  $table_caption      = $monthname[date("n",$date_string)-1] . " " . $year; // current
  if ($next_month == 13){
    $next_month = 1;
    $year++;
  }
  $table_caption_foll = $monthname[$next_month-1] . " " . $year;   // following

  /////////////////////////////////////////////
  //Print the calendar css code
  //
  echo "  <title>'Selecione a Data'</title>
    <style type=\"text/css\">
	   body { 
	   		  margin-top:0px;
			  margin-left:0px;
			  margin-right:0px;
   	 		  margin-bottom:0px;
		}
      a.cal_head
      {
        color: " . $head_link_color . ";
      }
      a.cal_head:hover
      {
        text-decoration: none;
      }
      .cal_head
      {
        background-color: " . $head_background_color . ";
        color:            " . $head_font_color . ";
        font-family:      " . $font_family . ";
        font-size:        " . $head_font_size . ";
        font-weight:      " . $head_font_weight . ";
        font-style:       " . $head_font_style . ";
      }
	  .cal_head_close
      {
        background-color: " . $head_background_color . ";
        color:            " . $head_font_color_close . ";
        font-family:      " . $font_family . ";
        font-size:        " . $head_font_size . ";
        font-weight:      " . $head_font_weight . ";
        font-style:       " . $head_font_style . ";
      }
      .cal_days /*darussol*/
      {
        background-color: " . $days_head_background_color . ";
        color:            " . $days_head_font_color . ";
        font-family:      " . $font_family . ";
        font-size:        " . $days_head_font_size . ";
        font-weight:      " . $days_head_font_weight . ";
        font-style:       " . $days_head_font_style . ";
      }
      .cal_content
      {
        background-color: " . $content_background_color . ";
        color:            " . $content_font_color . ";
        font-family:      " . $font_family . ";
        font-size:        " . $content_font_size . ";
        font-weight:      " . $content_font_weight . ";
        font-style:       " . $content_font_style . ";
      }
      .cal_today
      {
        background-color: " . $today_background_color . ";
        color:            " . $today_font_color . ";
        font-family:      " . $font_family . ";
        font-size:        " . $today_font_size . ";
        font-weight:      " . $today_font_weight . ";
        font-style:       " . $today_font_style . ";
      }
      .cal_event, a.cal_event /* e-man 17-06-04 */
      {
        background-color: " . $event_background_color . ";
        color:            " . $event_font_color . ";
        font-family:      " . $font_family . ";
        font-size:        " . $event_font_size . ";
        font-weight:      " . $event_font_weight . ";
        font-style:       " . $event_font_style . ";
      }
    </style>
  ";
  //
  /////////////////////////////////////////////


  /////////////////////////////////////////////
  //show events in popup?
  //
  if (isset ($_GET['show_event'])){
    list ($year, $month, $day) = explode ("-", $_GET['event_date']);
    $query = "
      SELECT *
      FROM " . $event_table . "
      WHERE EventYear  = '" . $year . "'
      AND   EventMonth = '" . $month . "'
      AND   EventDay   = '" . $day . "'
      ORDER BY EventTime ASC
    ";

    /* connect to the database */
    $database_connection = mysql_connect ($server, $username, $password);
    mysql_select_db ($database, $database_connection);
    $result = mysql_query ($query) or die(mysql_error());

    /* initize the variabele color_alternated (boolean) */
    $color_alternated = false;

    echo "<table width=\"100%\" border=\"" . $table_border . "\" cellpadding=\"" . $table_cellpadding . "\" cellspacing=\"" . $table_cellspacing . "\">";

    $date_string = mktime(0,0,0,$month,$day,$year);
    $month = sprintf("%01d",$month);

    echo "<tr><td align=\"center\" class=\"cal_head\" colspan=\"2\">".$day." " . $monthname[$month] . " ".$year."</td></tr>";

    /* loop through the results via a mysql_fetch_assoc () */
    while ($record = mysql_fetch_assoc ($result)){
      if ($color_alternated){
        $color_alternated = false;
        $background_color_row = $event_background_color;
      }
      else{
        $color_alternated = true;
        $background_color_row = $event_background_color2;
      }
      echo "<tr class=\"cal_event\">
              <td style=\"background-color:".$background_color_row."\" width=\"1\">" . $record['EventTime'] . "</td>
              <td style=\"background-color:".$background_color_row."\">" . nl2br($record['Event']) . "</td>
            </tr>";
    }
    /* close the table */
    echo "</table>";

    /* bring an exit so the script will terminate*/
    exit;
  }
  //
  /////////////////////////////////////////////

  /////////////////////////////////////////////
  //Print the calendar table header
  //
  echo "
    <script language=\"javascript\">
     function open_event(date_stamp){
       window.open(\"" . $calendar_script . "?show_event=true&event_date=\" + date_stamp, \"calendar_popup\",\"height=" . $event_popup_height . "\",\"width=".$event_popup_width."\");
     }
	 function close_event(date_stamp){
		 parent.document.getElementById('".$div_nome ."').style.display = 'none';
	 }
     function write_event(ano,mes,dia){
		 ano = ano + 2000;
		 if (dia < 10)
		 {
			 dia = '0'+dia;
		 }	
		 if (mes < 10)
		 {
			 mes = '0'+mes;
		 }	
 		 parent.document.getElementById('".$nome."').value = dia+'/'+mes+'/'+ano; 
		 parent.document.getElementById('".$div_nome."').style.display = 'none';
		 local = parent.document.getElementById('".$acao_nome."').value; 
		 if (local){
			 parent.confirma(local,'');
		 }	 
	 }
    </script>
     <table border=\"" . $table_border . "\" cellpadding=\"" . $table_cellpadding . "\" cellspacing=\"" . $table_cellspacing . "\" style=\"height:" . $table_height . "\" width=\"" . $table_width . "\">
      <tr>
        <td align=\"center\" class=\"cal_head\"><a class=\"cal_head\"  href=\"" . $_SERVER['PHP_SELF'] . "?" . $QUERY_STRING . "&amp;date=" .
                $previous_date . "\" title=\"" . $table_caption_prev . "\" >&laquo;</a></td>
        <td align=\"center\" class=\"cal_head\" colspan=\"4\">" . $table_caption . "</td>
        <td align=\"center\" class=\"cal_head\"><a class=\"cal_head\"   href=\"" . $_SERVER['PHP_SELF'] . "?" . $QUERY_STRING . "&amp;date=" .
                $next_date . "\" title=\"" . $table_caption_foll . "\"  >&raquo;</a></td>
        <td align=\"center\" class=\"cal_head\"><a class=\"cal_head_close\"   href=\"javascript:close_event()\" title=\"Fechar\"  >X</a></td>

      </tr>
      <tr>
        <td class=\"cal_days\">".$dayname[0]."</td>
        <td class=\"cal_days\">".$dayname[1]."</td>
        <td class=\"cal_days\">".$dayname[2]."</td>
        <td class=\"cal_days\">".$dayname[3]."</td>
        <td class=\"cal_days\">".$dayname[4]."</td>
        <td class=\"cal_days\">".$dayname[5]."</td>
        <td class=\"cal_days\">".$dayname[6]."</td>
      </tr><tr>
      ";
  //
  /////////////////////////////////////////////

  /////////////////////////////////////////////
  //The empty columns before the 1st day of the week
  //
  for( $i = 0 ; $i < $day_start; $i++ )
  {
    echo "<td class=\"cal_content\">&nbsp;</td>";
  }
  //
  /////////////////////////////////////////////

  $current_position = $day_start; //The current (column) position of the current day from the loop

  $total_days_in_month = date("t",$date_string); //The total days in the month for the end of the loop

  /////////////////////////////////////////////
  //Retrieve events for the current month + year
  //e-man : added 07 June 04
  if ($events_from_database)
  {
    $database_connection = mysql_connect ($server, $username, $password);
    mysql_select_db ($database, $database_connection);
    $result = mysql_query("
      SELECT *
      FROM " . $event_table . "
      WHERE
        EventYear = '" . $year . "'
      AND
        EventMonth = '" . $month . "'
    ");
    while ($record = mysql_fetch_assoc($result)){
      $event[$record['EventDay']] = $record;
    }
  }
  //
  /////////////////////////////////////////////

  /////////////////////////////////////////////
  //Loop all the days from the month
  //
  for( $i = 1; $i <= $total_days_in_month ; $i++)
  {
    $class = "cal_content";

    if( $i == date("j") && $month == date("n") && $year == date("Y") )
      $class = "cal_today";

    $current_position++;

    /* is there any event on this day? Yes, create a link. No clear the (previous) string */
    $link_start = "<a href=\"javascript:;\" class=\"cal_event\" onclick=\"javascript: write_event(".substr($year,2,2).",".$month.",".$i.");\">";
    $link_end   = "</a>";
    $class      = "cal_event";




    /* for the event filter */
    /* e-man : added 07 June 04 */
    $date_stamp = $year."-".$month."-".sprintf( "%02d",$i);

    echo "<td align=\"center\" class=\"" . $class . "\">" . $link_start . $i . $link_end . "</td>";
    if( $current_position == 7 )
    {
      echo "</tr><tr>\n";
      $current_position = 0;
    }
  }
  //
  /////////////////////////////////////////////

  $end_day = 7-$current_position; //There are

  /////////////////////////////////////////////
  //Fill the last columns
  //
  for( $i = 0 ; $i < $end_day ; $i++ )
    echo "<td class=\"cal_content\"></td>\n";
  //
  /////////////////////////////////////////////

  echo "</tr></table>";  // Close the table
?>
<script>
  focus();
</script>