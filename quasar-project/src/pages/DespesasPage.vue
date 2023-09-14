<template>
  <div>
    <q-table :rows="formattedDespesas" row-key="id" :columns="columns">
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <q-btn icon="mode_edit" @click="editDespesa(props.row)"></q-btn>
          <q-btn icon="delete" @click="deleteDespesa(props.row)"></q-btn>
        </q-td> </template
    ></q-table>

    <q-dialog v-model="editDialog" persistent>
      <q-card>
        <q-card-section>
          <q-form @submit="saveEditedDespesa">
            <q-input
              v-model="editedDespesa.descricao"
              label="Descricao"
            ></q-input>
            <q-input v-model="editedDespesa.valor" label="Valor"></q-input>
            <q-input
              v-model="editedDespesa.data"
              label="Data"
              mask="##/##/####"
              placeholder="dd/mm/yyyy"
            ></q-input>
            <q-btn label="Salvar" type="submit"></q-btn>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>

    <q-dialog v-model="deleteDialog" persistent>
      <q-card>
        <q-card-section>
          <p>Você tem certeza que deseja deletar essa Despesa?</p>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn label="Cancelar" color="primary" @click="cancelDelete"></q-btn>
          <q-btn
            label="Deletar"
            color="negative"
            @click="confirmDelete"
          ></q-btn>
        </q-card-actions>
      </q-card>
    </q-dialog>

    <q-form @submit="addDespesa" class="q-gutter-md">
      <div class="q-gutter-md row">
        <div class="col-3">
          <q-input
            v-model="newDespesa.descricao"
            label="Descrição"
            :rules="[
              (val) => val.length <= 191 || 'Insira no máximo 191 caracteres',
            ]"
          ></q-input>
        </div>
        <div class="col-3">
          <q-input
            v-model="newDespesa.valor"
            label="Valor"
            mask="#"
            reverse-fill-mask
            :rules="[
              (val) =>
                (val !== null && val !== '' && parseInt(val) != NaN) ||
                'Insira um valor',
            ]"
          ></q-input>
        </div>
        <div class="col-3">
          <q-input
            v-model="newDespesa.data"
            label="Data"
            mask="##/##/####"
            placeholder="dd/mm/yyyy"
            :rules="[validateDateRule]"
          ></q-input>
        </div>
        <div class="col-2">
          <q-btn label="Adicionar Despesa" type="submit"></q-btn>
        </div>
      </div>
    </q-form>
  </div>
</template>

<script>
import { api } from "boot/axios";
export default {
  data() {
    return {
      despesas: [],
      selectedDespesa: null,
      columns: [
        {
          name: "id",
          required: true,
          label: "ID",
          align: "left",
          field: "id",
          sortable: true,
        },
        {
          name: "descricao",
          required: true,
          label: "Descrição",
          align: "left",
          field: "descricao",
          sortable: true,
        },
        {
          name: "data",
          required: true,
          label: "Data",
          align: "left",
          field: "data",
          sortable: true,
        },
        {
          name: "valor",
          required: true,
          label: "Valor",
          align: "left",
          field: "valor",
          sortable: true,
        },
        {
          name: "actions",
          label: "Ações",
          align: "left",
        },
      ],
      editDialog: false,
      deleteDialog: false,
      validationErrors: {
        descricao: "",
        valor: "",
        data: "",
      },
      editedDespesa: {
        id: null,
        name: "",
        amount: null,
      },
      newDespesa: {
        name: "",
        amount: null,
      },
    };
  },
  async created() {
    await this.fetchDespesas.call(this);
  },
  computed: {
    formattedDespesas() {
      return this.despesas.map((despesa) => {
        return {
          ...despesa,
          data: new Date(
            new Date(despesa.data).setDate(new Date(despesa.data).getDate() + 1)
          ).toLocaleDateString("pt-BR"),
        };
      });
    },
  },

  methods: {
    validateDateRule(value) {
      const datePattern = /^(\d{2})\/(\d{2})\/(\d{4})$/;
      if (!datePattern.test(value)) {
        return "Insira uma data válida (dd/mm/yyyy)";
      }

      const [, day, month, year] = value.match(datePattern);
      const date = new Date(year, month - 1, day); 

      if (
        date instanceof Date &&
        !isNaN(date) &&
        date.getDate() === parseInt(day, 10) &&
        date.getMonth() === parseInt(month, 10) - 1 && 
        date.getFullYear() === parseInt(year, 10) &&
        date <= new Date()
      ) {
        return true;
      }

      return "Insira uma data válida (dd/mm/yyyy)";
    },

    async fetchDespesas() {
      try {
        const response = await api.get("/despesas");
        this.despesas = response.data.data;
      } catch (error) {
        console.error("Erro buscando Despesas:", error);
      }
    },
    deleteDespesa(despesa) {
      this.selectedDespesa = despesa;
      this.deleteDialog = true;
    },

    cancelDelete() {
      this.selectedDespesa = null;
      this.deleteDialog = false;
    },

    confirmDelete() {
      if (this.selectedDespesa) {
        const despesaId = this.selectedDespesa.id;
        api
          .delete(`/despesas/${despesaId}`)
          .then(() => {
            this.despesas = this.despesas.filter(
              (despesa) => despesa.id !== despesaId
            );
            this.selectedDespesa = null;
            this.deleteDialog = false;
          })
          .catch((error) => {
            console.error("Error deleting Despesa:", error);
          });
      }
    },
    handleDateInput(event) {
      this.newDespesa.data = new Date(event.target.value);
    },
    editDespesa(despesa) {
      this.editedDespesa = { ...despesa };
      this.editDialog = true;
    },
    saveEditedDespesa() {
      api
        .put(`/despesas/${this.editedDespesa.id}`, this.editedDespesa)
        .then(() => {
          const index = this.despesas.findIndex(
            (d) => d.id === this.editedDespesa.id
          );
          if (index !== -1) {
            this.despesas[index] = { ...this.editedDespesa };
          }
          this.editDialog = false;
        })
        .catch((error) => {
          console.error("Error updating Despesa:", error);
        });
      this.editDialog = false;
    },

    addDespesa() {
      const newDespesaData = {
        descricao: this.newDespesa.descricao,
        data: this.newDespesa.data,
        valor: parseFloat(this.newDespesa.valor),
      };
      api.post("/despesas", newDespesaData).then(() => {
        this.fetchDespesas.call(this);

        this.newDespesa = {
          descricao: "",
          data: null,
          valor: null,
        };
      });
    },
  },
};
</script>
