<?php

namespace ahvla;

use Illuminate\Http\Request;

class SubmissionUrl
{
    const CLASS_NAME = __CLASS__;
    /**
     * @var Request
     */
    private $request;

    function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function build($url, $draftSubId = null)
    {
        if ($draftSubId === null) {
            $draftSubId = $this->request->get('draftSubmissionId', null);
            if (!$draftSubId) {
                throw new \Exception('draftSubmissionId missing from request. Cannot build Url');
            }
        }

        if (strpos($url, '?')) {
            return $url . '&draftSubmissionId=' . $draftSubId;
        } else {
            return $url . '?draftSubmissionId=' . $draftSubId;
        }
    }

}