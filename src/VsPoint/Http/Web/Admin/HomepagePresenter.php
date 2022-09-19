<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Admin;

use Nette\Application\AbortException;
use Nette\Application\Attributes\Persistent;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Form;
use Solcik\Brick\DateTime\Clock;
use Symfony\Component\Stopwatch\Stopwatch;
use VsPoint\Domain\Locale\Locale\GetLocaleById;
use VsPoint\DTO\Common\SearchFormDTO;
use VsPoint\Entity\Locale\Locale;
use VsPoint\Exception\Runtime\Locale\Locale\LocaleNotFoundById;
use VsPoint\UI\Form\Common\SearchFormFactory;

final class HomepagePresenter extends BasePresenter
{
  public const LINK = ':Admin:Homepage:';

  #[Persistent]
  public string $q = '';

  public function __construct(
    private readonly Clock $clock,
    private readonly GetLocaleById $getLocaleById,
    private readonly SearchFormFactory $searchFormFactory,
  ) {
    parent::__construct();
  }

  /**
   * @throws AbortException
   * @throws LocaleNotFoundById
   */
  public function renderDefault(): void
  {
    $stopwatch = new Stopwatch();
    $stopwatch->start('Presenter');

    $timestamp = $this->clock->createZonedDateTime();

    $locale = $this->getLocaleById->__invoke(Locale::ENG);

    $formDTO = new SearchFormDTO($this->q);

    /** @var Form $form */
    $form = $this->getComponent('searchForm');
    $form->setDefaults($formDTO);

    $q = $this->q;

    $template = $this->createTemplate(HomepageTemplate::class);
    $template->setFile(__DIR__ . '/Homepage.latte');
    $template->q = $q;
    $template->locale = $locale;
    $template->stopwatch = $stopwatch;
    $template->timestamp = $timestamp;

    $response = new TextResponse($template);
    $stopwatch->stop('Presenter');
    $this->sendResponse($response);
  }

  protected function beforeRender(): void
  {
    parent::beforeRender();
    $this->redrawControl('title');
    $this->redrawControl('main');
  }

  protected function createComponentSearchForm(): Form
  {
    $form = $this->searchFormFactory->create('admin.' . self::class . '.search.placeholder');

    $form->onSuccess[] = function (Form $form, SearchFormDTO $data): void {
      $this->q = $data->query;

      if ($this->isAjax()) {
        $this->payload->postGet = true;
        $this->payload->url = $this->link('this');
      } else {
        $this->redirect(self::LINK);
      }
    };

    return $form;
  }
}
