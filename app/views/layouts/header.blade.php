<?php
$submission = false;
if(stristr(Route::currentRouteName(), 'step') !== false)
{
    $submission = true;
}
?>    
<header id="global-header" class="with-proposition">
    <div class="header-wrapper">
        <div class="header-global">
            <div class="header-logo">
                <a href="https://www.gov.uk/" title="Go to the GOV.UK homepage" id="logo" class="content">
                    <img src="/assets/images/gov.uk_logotype_crown.png?0.12.0" width="35" height="31" alt="Go to the GOV.UK homepage"> GOV.UK
                </a>
            </div>
        </div>
        <div class="header-proposition">
            <div class="content">
                <nav id="proposition-menu">
                    <h1 id="proposition-name">Animal Disease Testing Service</h1>

                @if(isset($loggedUser))
                    <ul id="proposition-links">
                        <?php
                            $currentUrl = Request::url();
                            if (preg_match('/\/landing/', $currentUrl)) {
                                $homeActive = true;
                            } else {
                                $homeActive = false;
                            }
                        ?>
                        <li>
                            <a id="home-page" href="/landing" @if($homeActive) class="active" @endif >Home</a>
                        </li>
                        <?php

                            if (preg_match('/\/step[1-7]/', $currentUrl) && isset($fullSubmissionForm)){
                                $draftSubmissionId = $fullSubmissionForm->draftSubmissionId;
                                if ( !empty($draftSubmissionId) ) {
                                    echo '<li>';
                                    echo '<a id="" class="js-dialog banner-right-cancel" submission-id="'.$draftSubmissionId.'" popup="cancel-submission" href="/cancel-submission-static/?draftSubmissionId='.$draftSubmissionId.'">Cancel</a>';
                                    echo '</li>';
                                }
                            }
                        ?>
                    </ul>
                @endif
                </nav>
            </div>
        </div>
    </div>
</header>