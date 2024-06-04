<?php
/**
 * Meetanshi_DeferJS
 *
 * @copyright   Copyright (c) 2023 IdeaInYou
 * @author      RuslanP <ruslan.p@ideainyou.com>
 */

namespace Meetanshi\DeferJS\Model;

/**
 * Class DefenderJsService for modifying Response Body
 */
class DeferJsService
{
    /**
     * Functions modifyResponseBody()
     *
     * @param mixed $response
     * @return mixed|void
     */
    public function modifyResponseBody($response)
    {
        $html = $response->getBody();
        if ($html == '') {
            return;
        }
        $conditionalJsPattern = '@(?:<script type="text/javascript"|<script)(.*)</script>@msU';
        preg_match_all($conditionalJsPattern, $html, $_matches);
        $jsIf = implode('', $_matches[0]);
        $html = preg_replace($conditionalJsPattern, '', $html);
        $html .= $jsIf;
        $response->setBody($html);

        return $response;
    }
}
