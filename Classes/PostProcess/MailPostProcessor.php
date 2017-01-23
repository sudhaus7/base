<?php
namespace SUDHAUS7\Sudhaus7Base\PostProcess;

class MailPostProcessor extends \TYPO3\CMS\Form\PostProcess\MailPostProcessor
{
    /**
     * Adds the carbon copy receiver of the mail message when configured
     *
     * Checks the address if it is a valid email address
     *
     * @return void
     */
    protected function setCc()
    {
        if (isset($this->typoScript['ccEmail'])) {
            $emails = $this->formUtility->renderItem(
                $this->typoScript['ccEmail.'],
                $this->typoScript['ccEmail']
            );
        } elseif ($this->getTypoScriptValueFromIncomingData('ccEmailField') !== null) {
            $emails = $this->getTypoScriptValueFromIncomingData('ccEmailField');
        }

        $validEmails = $this->filterValidEmails($emails);
        if (!empty($validEmails)) {
            $this->mailMessage->setCc($validEmails);
        }
    }
}