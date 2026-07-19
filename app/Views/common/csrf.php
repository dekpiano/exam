<meta name="csrf-token" content="<?= esc(csrf_hash()) ?>">
<script>
  // All AJAX writes from this page carry the CodeIgniter CSRF token.
  (() => {
    const originalFetch = window.fetch.bind(window);
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    window.fetch = (resource, options = {}) => {
      const method = (options.method || 'GET').toUpperCase();
      if (token && !['GET', 'HEAD', 'OPTIONS'].includes(method)) {
        const headers = new Headers(options.headers || {});
        headers.set('X-CSRF-TOKEN', token);
        options = { ...options, headers };
      }
      return originalFetch(resource, options);
    };
  })();
</script>
