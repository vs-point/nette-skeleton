<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Admin\Acl;

use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator;
use VsPoint\DTO\Acl\SignInFormDTO;
use VsPoint\Http\Web\Admin\BasePresenter;
use VsPoint\Http\Web\Admin\HomepagePresenter;
use VsPoint\UI\Form\Acl\SignInFormFactory;

final class SignInPresenter extends BasePresenter
{
  public const LINK = ':Admin:Acl:SignIn:';

  public function __construct(
    private readonly SignInFormFactory $signInFormFactory,
  ) {
    parent::__construct();
  }

  public function renderDefault(): void
  {
    $template = $this->createTemplate(SignInTemplate::class);
    $template->setFile(__DIR__ . '/SignIn.latte');

    $response = new TextResponse($template);
    $this->sendResponse($response);
  }

  protected function createComponentSignInForm(): Form
  {
    $form = $this->signInFormFactory->create();

    $form->onSuccess[] = function (Form $form, SignInFormDTO $data): void {
      $t = 'admin.' . SignInFormFactory::class;

      try {
        $securityUser = $this->getUser();
        $securityUser->login($data->email, $data->password);
      } catch (AuthenticationException $e) {
        if ($e->getCode() === Authenticator::NOT_APPROVED) {
          $form->addError("${t}.error.userInactive");
        } else {
          $form->addError("${t}.error.authenticationMismatch");
        }

        return;
      }

      $this->restoreRequest($this->backlink);
      $this->redirect(HomepagePresenter::LINK);
    };

    return $form;
  }
}
