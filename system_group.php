<!DOCTYPE html>
<html>
<head>
    <title>SNMP Manager</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>SNMP Manager</h1>

    <?php
    // Check if SNMP extension is enabled
    if (!extension_loaded('snmp')) {
        echo '<p>SNMP extension not enabled. Please enable it in your PHP configuration.</p>';
        exit;
    }

    // Function to get SNMP data from agent
    function getSnmpData($ip, $community, $oid)
    {
        try {
            return snmpget($ip, $community, $oid);
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    // SNMP agent details
    $agent_ip = '127.0.0.1:161'; 
    $community = 'public'; 

    // System Group
    $sys_descr = getSnmpData($agent_ip, $community, '1.3.6.1.2.1.1.1.0');
    $sys_contact = getSnmpData($agent_ip, $community, '1.3.6.1.2.1.1.4.0');
    $sys_name = getSnmpData($agent_ip, $community, '1.3.6.1.2.1.1.5.0');

    // ifTable (Interfaces group)
    $interfaces = snmp2_real_walk($agent_ip, $community, '1.3.6.1.2.1.2.2.1.2');

    // udpTable
    $udp_table = snmp2_real_walk($agent_ip, $community, '1.3.6.1.2.1.7.5.1.1');

    // tcpTable
    $tcp_table = snmp2_real_walk($agent_ip, $community, '1.3.6.1.2.1.6.13.1.1');

    ?>

    <!-- Display System Group in a table -->
    <h2>System Group</h2>
    <table>
        <tr>
            <th>sysDescr</th>
            <th>sysContact</th>
            <th>sysName</th>
        </tr>
        <tr>
            <td><?php echo $sys_descr; ?></td>
            <td><?php echo $sys_contact; ?></td>
            <td><?php echo $sys_name; ?></td>
        </tr>
    </table>

    <!-- Display ifTable (Interfaces group) -->
    <h2>Interfaces Group</h2>
    <table>
        <tr>
            <th>Interface</th>
            <th>Description</th>
        </tr>
        <?php foreach ($interfaces as $interface => $description) { ?>
            <tr>
                <td><?php echo $interface; ?></td>
                <td><?php echo $description; ?></td>
            </tr>
        <?php } ?>
    </table>

    <!-- Display udpTable -->
    <h2>UDP Table</h2>
    <table>
        <tr>
            <th>Local Address</th>
        </tr>
        <?php foreach ($udp_table as $address) { ?>
            <tr>
                <td><?php echo $address; ?></td>
            </tr>
        <?php } ?>
    </table>

    <!-- Display tcpTable -->
    <h2>TCP Table</h2>
    <table>
        <tr>
            <th>TCP Connection State</th>
        </tr>
        <?php foreach ($tcp_table as $state) { ?>
            <tr>
                <td><?php echo $state; ?></td>
            </tr>
        <?php } ?>
    </table>

</body>
</html>

