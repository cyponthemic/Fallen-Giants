<html>
    <head>
        <title><?php GMP_WP_PLUGIN_NAME. ' '. langGmp::_e('Plugin deactivation')?></title>
    </head>
    <body>
<div style="position: fixed; margin-left: 40%; margin-right: auto; text-align: center; background-color: #fdf5ce; padding: 10px; margin-top: 10%;">
    <div><?php langGmp::_e(GMP_WP_PLUGIN_NAME .' - plugin deactivation')?></div>
    <?php echo htmlGmp::formStart('deactivatePlugin', array('action' => $this->REQUEST_URI, 'method' => $this->REQUEST_METHOD))?>
    <?php
        $formData = array();
        switch($this->REQUEST_METHOD) {
            case 'GET':
                $formData = $this->GET;
                break;
            case 'POST':
                $formData = $this->POST;
                break;
        }
        foreach($formData as $key => $val) {
            if(is_array($val)) {
                foreach($val as $subKey => $subVal) {
                    echo htmlGmp::hidden($key. '['. $subKey. ']', array('value' => $subVal));
                }
            } else
                echo htmlGmp::hidden($key, array('value' => $val));
        }
    ?>
        <table width="100%">
            <tr>
                <td><?php langGmp::_e('Delete All data include Maps and Markers')?>:</td>
                <td><?php echo htmlGmp::radiobuttons('deleteAllData', array('options' => array('No', 'Yes')))?></td>
            </tr>
        </table>
    <?php echo htmlGmp::submit('toeGo', array('value' => langGmp::_('Done')))?>
    <?php echo htmlGmp::formEnd()?>
    </div>
</body>
</html>