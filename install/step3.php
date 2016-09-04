<table width='100%' class='outer' cellspacing='5'>
<tr>
    <th colspan='2'></th>
</tr>
<tr valign='top' align='left'>
    <td class='head'>
        <b>Database</b><br />
        <span style='font-size:85%;'>Choose the database to be used</span>
    </td>
    <td class='even'>
        <select  size='1' name='database' id='database'><option value='mysql' selected='selected''>mysql</option></select>
    </td>
</tr>
<tr valign='top' align='left'>
    <td class='head'>
        <b>Database Hostname</b><br />
        <span style='font-size:85%;'>Hostname of the database server. If you are unsure, 'localhost' works in most cases.</span>
    </td>
    <td class='even'>
        <input type='text' name='dbhost' id='dbhost' size='30' maxlength='100' value='localhost' />
    </td>
</tr>
<tr valign='top' align='left'>
    <td class='head'>
        <b>Database Username</b><br />
        <span style='font-size:85%;'>Your database user account on the host</span>
    </td>
    <td class='even'>
        <input type='text' name='dbuser' id='dbuser' size='30' maxlength='100' value='nacin' />
    </td>
</tr>
<tr valign='top' align='left'>
    <td class='head'>
        <b>Database Password</b><br />
        <span style='font-size:85%;'>Password for your database user account</span>
    </td>
    <td class='even'>
        <input type='text' name='dbpass' id='dbpass' size='30' maxlength='100' value='nican' />
    </td>
</tr>
<tr valign='top' align='left'>
    <td class='head'>
        <b>Database Name</b><br />
        <span style='font-size:85%;'>The name of database on the host. The installer will attempt to create the database if not exist</span>
    </td>
    <td class='even'>
        <input type='text' name='dbname' id='dbname' size='30' maxlength='100' value='nacin' />
    </td>
</tr>
</table>