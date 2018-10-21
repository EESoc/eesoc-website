<?php

/**
 * Emulate authentication functions provided by the server
 */
function pam_auth($user, $pass)
{
    return ($user === $pass);
}

function ldap_get_name($user)
{
    return ($user === 'cmp14') ? 'Chung Poon' : null;
}

function ldap_get_mail($user)
{
    return ($user === 'cmp14') ? 'cmp14@imperial.ac.uk' : null;
}

function ldap_get_info($user)
{
    if ($user === 'cmp14')
    {
        return array(
            'Electrical & Electronic Engineering (MEng 4YFT)',
            'Undergraduate',
            'Electrical and Electronic Engineering',
            'Engineering',
            'Imperial College'
        );
    }
    else
    {
        return null;
    }
}
