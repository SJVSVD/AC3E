<template>
  <div class="container mt-4">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body p-4" style="min-height: 400px;">
            <div>
              <iframe
                class="w-100"
                style="max-width: 100%; height: 800px;"
                src="https://app.powerbi.com/view?r=eyJrIjoiZTk5NzliNjMtNGYzMS00YTFjLTgzZDItZDkwZWUzNzk5ZWJkIiwidCI6IjAyNjI1Njc2LTMyMjctNDQwYS05YzY4LWJiNmQyOWRlNDIwNiIsImMiOjR9"
                frameborder="0"
                allowFullScreen="true"
              ></iframe>
            </div>
            <!-- Aquí podrías agregar un botón o funcionalidad relacionada al portapapeles si fuera necesario -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      clipboardSupported: true,
    };
  },
  mounted() {
    // Comprobación si ClipboardItem está definido
    if (typeof window.ClipboardItem === "undefined") {
      console.warn("ClipboardItem no está definido en este navegador.");
      this.clipboardSupported = false;

      // Solución alternativa si ClipboardItem no está disponible
      if (typeof ClipboardItem === 'undefined') {
        // eslint-disable-next-line no-undef
        window.ClipboardItem = function(items) {
          return items; // Simulando el comportamiento de ClipboardItem
        };
      }
    }
  },
};
</script>
