// eslint-disable-next-line import/prefer-default-export
export const onError = {
  initialize(naja) {
    naja.addEventListener('error', () => {
      const toast = document.createElement('div');
      toast.classList.add('toast');
      toast.innerHTML =
        '<strong>There was an error processing the request :(</strong>Please try again later.';
      document.body.appendChild(toast);
      window.setTimeout(() => document.body.removeChild(toast), 2000);
    });
  },
};
