<template>
  <div class="card">
    <h3 class="card-header text-center font-weight-bold text-uppercase py-4">Csv name</h3>
    <div class="card-body">
      <div id="table" class="table-editable">
        <span class="table-add float-right mb-3 mr-2"><a href="#!" class="text-success"><i
              class="fas fa-plus fa-2x" aria-hidden="true"></i></a></span>
        <table class="table table-bordered table-responsive-md table-striped text-center">
          <thead>
            <tr>
              <th class="text-center"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="">
              <td class="pt-3-half" contenteditable="true"></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
  export default {
    props: {
      categories: Array,
      products: Array
    }
  }
</script>
