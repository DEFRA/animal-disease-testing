<?php
    $atLastStep = preg_match('~step8~', Request::url())?true:false;

    if (isset($isSop) && $isSop) {
        $steps = [
            ['url' => $subUrl->build('step5'), 'caption' => 'Your basket', 'step' => '5'],
            ['url' => $subUrl->build('step6'), 'caption' => 'Delivery address', 'step' => '6'],
            ['url' => $subUrl->build('step7'), 'caption' => 'Review and submit', 'step' => '7'],
            ['url' => $subUrl->build('step8'), 'caption' => 'Print documents', 'step' => '8'],
        ];
    } else {
        $steps = [
            ['url' => $subUrl->build('step1'), 'caption' => 'Client details', 'step' => '1' ],
            ['url' => $subUrl->build('step2'), 'caption' => 'Animal details', 'step' => '2'],
            ['url' => $subUrl->build('step3'), 'caption' => 'Clinical history', 'step' => '3'],
            ['url' => $subUrl->build('step4'), 'caption' => 'Choose tests', 'step' => '4'],
            ['url' => $subUrl->build('step5'), 'caption' => 'Your basket', 'step' => '5'],
            ['url' => $subUrl->build('step6'), 'caption' => 'Delivery address', 'step' => '6'],
            ['url' => $subUrl->build('step7'), 'caption' => 'Review and submit', 'step' => '7'],
            ['url' => $subUrl->build('step8'), 'caption' => 'Print documents', 'step' => '8'],
            ];
    }

    if ($fullSubmissionForm->submissionType == 'routine'){
        $steps[2]['caption'] = 'Sample details';
    }

?>
<div class="steps-bar">
	<ol>
    <?php
        $currentUrl = Request::fullUrl();

        foreach($steps as $stepIndex => $data)
        {
            $stepIndexDisplay = $stepIndex+1;

            if (strstr($currentUrl, $data['url'])) {
                $highlight = "step-highlight";
            }
            else {
                $highlight = null;
            }

            $data['caption'] = $data['caption'];
            $data['url'] = htmlentities($data['url']);

            echo "<li><span class='number'>" . $stepIndexDisplay . "</span>";

            if(preg_match('~^step8~', $data['url'])&& !$atLastStep){
                echo '<span class="step-title">'.$data['caption'].'</span>';
            }elseif(!preg_match('~^step8~', $data['url']) && $atLastStep){
                echo '<span class="step-title">'.$data['caption'].'</span>';
            }else{
                echo "<a id='step_". $data['step'] ."' class='" . $highlight . "' href='/" . $data['url'] . "'>" . $data['caption'] . "</a>";
            }

            echo "</li>";
        }
    ?>
   </ol>
</div>
