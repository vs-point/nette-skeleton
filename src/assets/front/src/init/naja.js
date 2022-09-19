import whenDomReady from 'when-dom-ready';
import naja from 'naja';
import netteForms from 'nette-forms';

import { SpinnerExtension } from '../../../@common/src/naja/extension/spinner';
import { onError } from '../../../@common/src/naja/extension/onError';

whenDomReady().then(() => {
  naja.formsHandler.netteForms = netteForms;

  naja.registerExtension(onError);
  naja.registerExtension(new SpinnerExtension('#global-spinner'));

  naja.initialize();
});
