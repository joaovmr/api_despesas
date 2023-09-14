<template>
  <div>
    <!-- List Despesas -->
    <q-table :rows="formattedDespesas" row-key="id" :columns="columns">
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <q-btn icon="mode_edit" @click="editDespesa(props.row)"></q-btn>
          <q-btn icon="delete" @click="deleteDespesa(props.row)"></q-btn>
        </q-td> </template
    ></q-table>

    <!-- Edit Despesa Dialog -->
    <q-dialog v-model="editDialog" persistent>
      <q-card>
        <q-card-section>
          <!-- Edit Despesa Form -->
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
            <!-- Add more fields as needed -->
            <q-btn label="Salvar" type="submit"></q-btn>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>

    <!-- Delete Confirmation Dialog -->
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

    <!-- Add New Despesa Form -->
    <q-form @submit="addDespesa" class="q-gutter-md">
      <div class="q-gutter-md row">
        <div class="col-3">
          <q-input v-model="newDespesa.descricao" label="Descrição"></q-input>
        </div>
        <div class="col-3">
          <q-input v-model="newDespesa.valor" label="Valor"></q-input>
        </div>
        <div class="col-3">
          <q-input
            v-model="newDespesa.data"
            label="Data"
            mask="##/##/####"
            placeholder="dd/mm/yyyy"
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
    try {
      const authToken = localStorage.getItem("authToken");
      const headers = {
        Authorization: `Bearer ${authToken}`,
      };
      const response = await api.get("/despesas", {
        headers,
      });
      this.despesas = response.data.data;
    } catch (error) {
      console.error("Error fetching Despesas:", error);
    }
  },
  computed: {
    formattedDespesas() {
      return this.despesas.map((despesa) => {
        return {
          ...despesa,
          data: new Date(despesa.data).toLocaleDateString("pt-BR"), // Format to dd/mm/yyyy
        };
      });
    },
  },
  methods: {
    deleteDespesa(despesa) {
      this.selectedDespesa = despesa;
      this.deleteDialog = true;
    },

    cancelDelete() {
      this.selectedDespesa = null; // Clear the selected Despesa
      this.deleteDialog = false; // Close the delete dialog
    },

    confirmDelete() {
      const authToken = localStorage.getItem("authToken");
      const headers = {
        Authorization: `Bearer ${authToken}`,
      };
      if (this.selectedDespesa) {
        const despesaId = this.selectedDespesa.id;
        api
          .delete(`/despesas/${despesaId}`, {
            headers,
          })
          .then(() => {
            this.despesas = this.despesas.filter(
              (despesa) => despesa.id !== despesaId
            );
            this.selectedDespesa = null; // Clear the selected Despesa
            this.deleteDialog = false; // Close the delete dialog
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
      // Open the edit dialog and populate editedDespesa with the selected Despesa data
      this.editedDespesa = { ...despesa };
      this.editDialog = true;
    },
    saveEditedDespesa() {
      
      const authToken = localStorage.getItem("authToken");
      const headers = {
        Authorization: `Bearer ${authToken}`,
      };
      api
        .put(`/despesas/${this.editedDespesa.id}`, this.editedDespesa, {
          headers,
        })
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
      const authToken = localStorage.getItem("authToken");
      const headers = {
        Authorization: `Bearer ${authToken}`,
      };
      const newDespesaData = {
        descricao: this.newDespesa.descricao,
        data: this.newDespesa.data, // Make sure it's a valid date
        valor: parseFloat(this.newDespesa.valor), // Parse to a numeric value
      };
      api
        .post("http://127.0.0.1:8000/api/despesas", newDespesaData, { headers })
        .then((response) => {
          this.despesas.push(response.data);

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
