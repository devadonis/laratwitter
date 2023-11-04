<template>
  <div class="container">
    <div class="row"></div>
    <hr />
    <div class="row">
      <div class="col"></div>
      <div class="col-md-8">
        <section class="list">
          <header>Tasks</header>
          <input v-model="newTask" class="add-new" @keyup.enter="addTask()" placeholder="Type here and press enter..." />
          <draggable class="drag-area" :list="tasksNew" :options="{ animation: 200 }" :element="'article'"
            @change="updateOrder()">
            <article class="card" v-for="task in tasksNew" :key="task.id" :data-id="task.id">
              <div class="task" @dblclick="onEditing(task)">
                <input type="checkbox" :checked="task.status" @change="updateStatus(task)" />
                <input v-if="task === editingTask" type="text" v-model="taskContent" @keyup.enter="updateContent(task.id)" class="edit-task" />
                <span v-else :class="task.status ? 'line-through' : ''">{{ task.title }}</span>
                <button type="button" class="delete-btn" @click="deleteTask(task.id)">
                  <span class="delete-icon"></span>
                </button>
              </div>
            </article>
          </draggable>
        </section>
      </div>
      <div class="col"></div>
    </div>
  </div>
</template>

<script>
import draggable from 'vuedraggable'

export default {
  components: {
    draggable
  },
  props: ['tasks'],
  data() {
    return {
      tasksNew: this.tasks,
      newTask: "",
      taskContent: "",
      editingTask: null
    }
  },
  methods: {
    addTask() {
      if (this.newTask !== "") {
        const title = this.newTask,
              order = this.tasksNew.length + 1;

        axios.post('/tasks', { title, order })
          .then(response => {
            if (response.data.status) {
              this.tasksNew.push(response.data.data);
              this.newTask = "";
            }
          }).catch(error => {
            console.log(error);
          })
      }
    },

    updateStatus(task) {
      axios.patch(`/tasks/${task.id}/status`, {
        status: !task.status
      }).then((response) => {
        if (response.data.status) {
          const updatedTasks = this.tasksNew.map(task => {
            if (task.id === response.data.data.id) {
              return response.data.data;
            }
            return task;
          });

          this.tasksNew = updatedTasks;
        }
      }).catch((error) => {
        console.log(error);
      })
    },

    onEditing(task) {
      this.editingTask = task;
      this.taskContent = task.title;
    },

    updateContent(id) {
      if (this.taskContent !== "") {
        axios.patch(`/tasks/${id}/content`, {
          title: this.taskContent
        }).then(response => {
          if (response.data.status) {
            const updatedTasks = this.tasksNew.map(task => {
              if (task.id === response.data.data.id) {
                return response.data.data;
              }
              return task;
            });
  
            this.editingTask = null;
            this.taskContent = "";
            this.tasksNew = updatedTasks;
          }
        })
      }
    },

    updateOrder() {
      this.tasksNew.map((task, index) => {
        task.order = index + 1;
      });

      axios.put('/tasks/updateAll', {
        tasks: this.tasksNew
      }).then((response) => {
        console.log(response.data);
      }).catch((error) => {
        console.log(error);
      })
    },

    deleteTask(id) {
      axios.delete(`/tasks/${id}`)
      .then(response => {
        if (response.data.status) {
          const newTasks = this.tasksNew.filter(task => task.id !== response.data.data);
          this.tasksNew = newTasks;
        }
      })
      .catch(error => {
        console.log(error);
      })
    }
  }
}
</script>

<style>
.list {
  background-color: #26004d;
  border-radius: 3px;
  margin: 5px 5px;
  padding: 10px;
  width: 100%;
}

.list>header {
  margin: 15px;
  font-weight: bold;
  color: white;
  text-align: center;
  font-size: 20px;
  line-height: 28px;
  cursor: grab;
}

.list article {
  border-radius: 3px;
  margin-top: 10px;
}

.list .card {
  background-color: lightblue;
  border-bottom: 1px solid transparent;
  padding: 15px 10px;
  cursor: pointer;
  font-size: 16px;
  font-weight: bolder;
}

.list .card:hover {
  background-color: aquamarine;
}

.list .add-new, .task .edit-task {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid transparent;
  border-radius: 3px;
  outline: none;
  font-size: 14px;
}

.list .task {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.list .task > * {
  margin-right: 10px;
}

.task .delete-btn {
  background-color: red;
  color: white;
  margin: 0;
  padding: 0 8px;
  border: 1px solid transparent;
  border-radius: 50%;
  outline: none;
}

.task .line-through {
  text-decoration: line-through;
  color: green;
}

.delete-btn .delete-icon::before {
  content: "\00D7";
}

.drag-area {
  min-height: 10px;
}
</style>