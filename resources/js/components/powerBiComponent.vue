<template>
  <div class="container mt-4">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body p-4" style="min-height: 400px;">
            <div v-if="clipboardSupported">
              <iframe
                class="w-100"
                style="max-width: 100%; height: 800px;"
                src="https://app.powerbi.com/view?r=eyJrIjoiZTk5NzliNjMtNGYzMS00YTFjLTgzZDItZDkwZWUzNzk5ZWJkIiwidCI6IjAyNjI1Njc2LTMyMjctNDQwYS05YzY4LWJiNmQyOWRlNDIwNiIsImMiOjR9"
                frameborder="0"
                allowFullScreen="true"
              ></iframe>
            </div>
            <div v-else class="text-center text-danger">
              Este navegador no es compatible con algunas funciones requeridas por Power BI. Intenta con otro navegador actualizado como Chrome o Edge.
            </div>
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
    if (typeof window.ClipboardItem === "undefined") {
      console.warn("ClipboardItem no está definido en este navegador.");
      this.clipboardSupported = false;
    }
  },
};
</script>

