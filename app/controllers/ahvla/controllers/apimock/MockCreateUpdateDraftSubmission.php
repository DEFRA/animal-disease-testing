<?php

namespace ahvla\controllers\apimock;


use Controller;
use Illuminate\Http\Request;
use Input;

class MockCreateUpdateDraftSubmission extends Controller
{
    /**
     * @var Request
     */
    private $request;

    function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function createSubmission()
    {
        $jsonContent = $this->request->instance()->getContent();

        file_put_contents(
            __DIR__ . '/createUpdateDraftSubmission' . uniqid() . '.json',
            $jsonContent
        );

        $content = json_decode($jsonContent);

        if ($content->draftSubmissionId) {
            return ['draftSubmissionId' => $content->draftSubmissionId];
        } else {
            return ['draftSubmissionId' => $this->ran4() . '-' . $this->ran4() . '-' . $this->ran4() . '-' . $this->ran4()];
        }
    }

    private function ran4()
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        for ($i = 0; $i < 4; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        }
        return strtoupper($string);
    }

}