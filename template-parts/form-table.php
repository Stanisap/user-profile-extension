<?php

include( WP_PLUGIN_DIR.'/user-profile-extension/lib/CriptRSA.php' );
$rsa = new CriptRSA();
add_action( 'show_user_profile', 'show_profile_fields' );
add_action( 'edit_user_profile', 'show_profile_fields' );
function show_profile_fields( $user )
{
    global $rsa;
    $address = $rsa->decode(esc_attr(get_the_author_meta('address',$user->ID)));
    $phone = $rsa->decode(get_the_author_meta('phone',$user->ID));
?>
    <h3>Дополнительная информация</h3>
    <table class="form-table">
        <tr><th><label for="address">Адрес</label></th>
            <td><input type="text" name="address" id="address" value="<?php echo $address;?>" class="regular-text" /><br /></td>
        </tr>
        <tr><th><label for="phone">Телефон</label></th>
            <td><input type="phone" name="phone" id="phone" value="<?php echo $phone;?>" class="regular-text" /><br /></td>
        </tr>
        <tr>
            <th><label for="gender">Пол</label></th>
            <td><?php $gender = $rsa->decode(get_the_author_meta('gender',$user->ID )); ?>
                <ul>
                    <li><label><input value="мужской" name="gender"<?php if ($gender == 'мужской') { ?> checked="checked"<?php } ?> type="radio" /> мужской</label></li>
                    <li><label><input value="женский"  name="gender"<?php if ($gender == 'женский') { ?> checked="checked"<?php } ?> type="radio" /> женский</label></li>
                </ul>
            </td>
        </tr>
        <tr>
        <th><label for="fstatu">Семейный статус</label></th>
            <td><?php $fstatus = $rsa->decode(get_the_author_meta('fstatus',$user->ID )); ?>
                <ul>
                    <li><label><input value="в брак" name="fstatus"<?php if ($fstatus == 'в брак') { ?> checked="checked"<?php } ?> type="radio" /> в брак</label></li>
                    <li><label><input value="не в браке"  name="fstatus"<?php if ($fstatus == 'не в браке') { ?> checked="checked"<?php } ?> type="radio" /> не в браке</label></li>
                </ul>
            </td>
        </tr>
    </table>
<?php
}


