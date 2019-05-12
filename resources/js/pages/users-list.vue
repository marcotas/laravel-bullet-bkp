<template>
    <div>
        <h1>User List</h1>

        <div class="card">
            <div class="card-body pb-2">
                <div class="card-title">Users List</div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Jetete</td>
                            <td>Adf</td>
                            <td>
                                <button class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                    Oi
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    created() {
        this.fetchUsers();
    },

    methods: {
        async fetchUsers() {
            const response = await axios.get("/users");
        }
    }
};
</script>
