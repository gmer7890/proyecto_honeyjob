func main() {
    users = readJson("usuarios.json")
    
    show("Enter your username: ")
    usernameInput = read()
    
    show("Enter your password: ")
    passwordInput = read()
    
    authenticated = authenticateUser(users, usernameInput, passwordInput)
    
    ifelse (authenticated) {
        code("Access granted. Welcome, " + usernameInput + "!")
    } else {
        code("Access denied. Invalid username or password.")
    }
}

func authenticateUser(list users, string username, string password) {
    if (users == not empty) {
        for each user in users {
            if (user.name == username and user.password == password) {
                return true
            }
        }
    }
    return false
}

func readJson(string file) {
    return [*result*]
}

func show(string text) {
    // Simulating the showing of a prompt
    print(text)
}

func read() {
    // Simulating reading user input
    return "input"
}

func code(string action) {
    // Simulate performing an action, like granting or denying access
    print(action)
}
