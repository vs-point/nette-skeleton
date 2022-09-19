import whenDomReady from 'when-dom-ready';

// eslint-disable-next-line import/prefer-default-export
export class SpinnerExtension {
  constructor(fallbackSelector) {
    this.fallbackSelector = fallbackSelector;
  }

  initialize(naja) {
    whenDomReady().then(() => {
      const mainContent = document.querySelector(this.fallbackSelector);

      naja.uiHandler.addEventListener('interaction', (event) => {
        const { options } = event.detail;
        options.spinnerTarget = event.detail.element.querySelector('[data-spinner]');
      });

      naja.addEventListener('start', (event) => {
        const el =
          event.detail.options.spinnerTarget != null
            ? event.detail.options.spinnerTarget
            : mainContent;
        el.classList.remove('d-none');
      });

      naja.addEventListener('complete', (event) => {
        const el =
          event.detail.options.spinnerTarget != null
            ? event.detail.options.spinnerTarget
            : mainContent;
        el.classList.add('d-none');
      });
    });
  }
}
