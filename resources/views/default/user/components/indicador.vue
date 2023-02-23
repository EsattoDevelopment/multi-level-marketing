<script>
    export default {
        props: ['users', 'patrocinio'],


        method:{
            onChange(value) {
                window.console.log(value);
                //this.$http.get('http://localhost:8000/api/user/busca').then((req) => this.users = req.data);
            }
        },

        ready () {
            this.$http.get('http://localhost:8000/api/user/busca').then((req) => this.users = req.data)
        }
    }
</script>

<template>
    <div>
        <div class="form-group">
            <label>Indicador</label>
            <select class="form-control select2" name="indicador"
                    data-placeholder="Selecione um indicador" style="width: 100%;">
                <option v-for="u in users" selected="{{ u.id == patrocinio }}" value="{{ u.id }}">#{{ u.id }} - {{ u.name }}</option>
            </select>

        </div>
    </div>
</template>