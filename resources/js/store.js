import { reactive, readonly } from 'vue';

// Create a reactive object to store the user data
const state = reactive({
  user: null,
});

// Create a getter to access the user object as read-only
const getUser = () => readonly(state.user);

// Create a setter to update the user object
const setUser = (user) => {
  state.user = user;
};

// Export the getter and setter
export { getUser, setUser };
