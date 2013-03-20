<!-- Hide
// define variables
var month_names = new Array("Ene -","Feb -","Mar -","Abr -","May -","Jun -","Jul -","Ago -","Sep -","Oct -","Nov -","Dic -");
var days_in_month  = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
var this_date = new Date();                                   // get todays date
var this_day = this_date.getDate();                           // returns date within month - range 1-31
var this_month = this_date.getMonth();                        // returns month within year - range 0-11 
 
function makeCalendar(the_month, the_year)
{   first_of_month = new Date(the_year, the_month, 1);        // creates instance of date object for first of month
    day_of_week = first_of_month.getDay();                    // returns day within week - range 0-6
    if  (((the_year % 4 == 0) && (the_year % 100 != 0)) || (the_year % 400 == 0))
    {   days_in_month[1] = 29;                                // it's a leap year so change # days in Feb in array
    }
    else
    {   days_in_month[1] = 28;                                // not leap year - future use if multi year calendar built
    }
    document.write("<TABLE CALLSPACING=0 CELLPADDING=0>");    // start building the month table
    document.write("<TR BGCOLOR='#D2D2D2'><TH COLSPAN=7>" + month_names[the_month] + " " + the_year);        // month and year heading
    document.write("<TR BGCOLOR='#2D96FF'><TH BGCOLOR='#FF6262'>D</TH><TH>L</TH><TH>M</TH><TH>M</TH><TH>J</TH><TH>V</TH><TH>S</TH></TR>");    // day of week heading
    document.write("<TR ALIGN=RIGHT>");
    var column = 0;
    for (i=0; i<day_of_week; i++)                             // skip to day_of_week value for first_of_month
    {   document.write("<TD> </TD>");
        column++;
    }
    for (i=1; i<=days_in_month[the_month]; i++)
    {   if  ((i == this_day)  && (the_month == this_month) && (the_year == this_year))
        {   document.write("<TD BGCOLOR='#FF6262'><B>" + i + "</B></TD>");       // highlite todays date
        }
        else
        {   document.write("<TD BGCOLOR='#A6C9E6'><B>" + i + "</B></TD>");       // no highlite for other dates
        }
        column++;
        if  (column == 7)                                     // start next row of dates for month
        {   document.write("</TR><TR ALIGN=RIGHT>");
            column = 0;
        }
    }
    document.write("</TR></TABLE>");                          // month complete - close table
}
    
function y2K(number)                                          // if year < 2000 javascript gives only 2 digits for year
{ return (number < 1000) ? number + 1900 : number; 
}
 
var this_year = y2K(this_date.getYear());
// -->

//*----------COLOCA ESTO EN EL BODY----------*
//<body>
//<SCRIPT LANGUAGE="JavaScript">

//document.write("<TABLE BORDER='1'><TR VALIGN=TOP><TD>");
//makeCalendar(this_month, this_year);                                   // we are passing the month and year to build the calendar
//document.close();

//</SCRIPT>
//</body>