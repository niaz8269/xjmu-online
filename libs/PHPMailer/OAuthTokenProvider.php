<?php

namespace PHPMailer\PHPMailer;

/**
 * PHPMailer OAuth token provider interface.
 */
interface OAuthTokenProvider
{
    /**
     * Generate a base64-encoded OAuth token.
     *
     * @return string
     */
    public function getOauth64();
}
