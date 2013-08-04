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
    return ($user === 'jyl111') ? 'Jian Lee' : null;
}

function ldap_get_mail($user)
{
    return ($user === 'jyl111') ? 'jyl111@imperial.ac.uk' : null;
}

function ldap_get_info($user)
{
    if ($user === 'jyl111')
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