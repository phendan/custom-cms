import axios from 'axios'

const form = document.querySelector('.login-form')

form?.addEventListener('submit', async e => {
    e.preventDefault()

    const email = document.querySelector<HTMLInputElement>('#email')?.value
    const password = document.querySelector<HTMLInputElement>('#password')?.value

    // Client Side Validation

    try {
        const response = await axios.post('/login', {
            email,
            password
        })

        console.log(response)
    } catch (error: any) {
        console.log(error)
    }
})
