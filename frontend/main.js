'use strict'

 const openModal = () => document.querySelector('#modal_comprov').classList.add("active")
 const closeModal = () => document.querySelector('#modal_comprov').classList.remove('active')

 const openModalExit = () => document.querySelector('#modal-exit').classList.add('active');
const closeModalExit = () => document.querySelector('#modal-exit').classList.remove('active');


const getPreco = async (url) => {
    const response = await fetch(url)
    const json = await response.json()
    return await json
}



const getClient = async (url) => {
    const response = await fetch(url)
    const json = await response.json()
    return await json
}



const updateTable = async () => {
    clearTable()
    const client = await getClient('http://fastparking.com.br/clientes')
    client.forEach(createRow)
}

const clearInput = () => {
    document.querySelector('#nome').value = ''
    document.querySelector('#placa').value = ''
}

const clearTable = () => {
    const recordClient = document.querySelector('#table tbody')
    while (recordClient.firstChild) {
        recordClient.removeChild(recordClient.lastChild)
    }
}

const createRow = async (client) => {
    const recordClient = document.querySelector('#table tbody')
    const newTr = document.createElement('tr')
        newTr.innerHTML = `
        <td>${client.nome}</td>
        <td>${client.placa}</td>
        <td>${client.dataHoraEntrada}</td>
        <td>
            <button type='button' class='button green' data-action="comp-${client.id}">Comprovante</button>
            <button type='button' class='button blue' data-action="editar-${client.id}">Editar</button>
            <button type='button' class='button red' data-action="saida-${client.id}" >Saida</button>
        </td>
    `
        recordClient.appendChild(newTr)
    }


const setClient = async (newClient) => {
    const url = 'http://fastparking.com.br/clientes'
    const options = {
        method: 'POST',
        body: JSON.stringify(newClient)
    }
    console.log(newClient)
    await fetch(url, options)
}


const isValidForm = () => document.querySelector('#inputs').reportValidity()
const saveClient = async () => {
    if (isValidForm()) {

        const newClient = {
            nome: document.querySelector('#nome').value,
            placa: document.querySelector('#placa').value,
        }

        const index = document.querySelector('#nome').dataset.index

        if (index == '') {
            await setClient(newClient)
            console.log("inserido")

        } else {
            updateClient(newClient, index)
            console.log("Editado")

        }
        clearInput()
        updateTable()
    }
}

const compClient = async (index) => {
    openModal()
    const client = await getClient(`http://fastparking.com.br/clientes/${index}`)
    const input = Array.from(document.querySelectorAll('#form_comprovante input'));
    input[0].value = client.nome;
    input[1].value = client.placa;
    input[2].value = client.dataHoraEntrada;
}

const updateClient = async (newClient, id) => {
    const client = await getClient(`http://fastparking.com.br/clientes/${id}`)
    const url = `http://fastparking.com.br/clientes/${client.id}`
    const options = {
        method: 'PUT',
        body: JSON.stringify(newClient)
    }
    await fetch(url, options)
}

const editClient = async (id) => {
    const client = await getClient(`http://fastparking.com.br/clientes/${id}`)
    document.querySelector('#nome').value = client.nome
    document.querySelector('#placa').value = client.placa
    document.querySelector('#nome').dataset.index = id

}

const deleteClient = async (id) => {
    const url = `http://fastparking.com.br/clientes/${id}`;
    const opitions = {
        method: 'DELETE'
    }
    await fetch(url, opitions);
}

const saidaClient = async (id) => {
    openModalExit()
    await deleteClient(id);

    const client = await getClient(`http://fastparking.com.br/clientes/${id}`);

    const input = Array.from(document.querySelectorAll('#form-exit input'));
    input[0].value = client.nome;
    input[1].value = client.placa;
    input[2].value = client.dataHoraEntrada;
    input[3].value = client.dataHoraSaida;
    input[4].value = client.valorPagar;

    updateTable();
}

const actionButttons = (event) => {
    const element = event.target
    if (element.type === 'button') {
        const action = element.dataset.action.split('-')
        if (action[0] === 'comp') {
            compClient(action[1])
        } else if (action[0] === 'editar') {
            editClient(action[1])
        } else {
            saidaClient(action[1])
        }
    }
}
 const get = (number) => {

     number = number.replace(/(^.{3}$)/,'$1-')
     number = number.replace(/(^.{9}$)/,'')
     return number
 }
 const mask  =(event) =>{
      event.target.value = get (event.target.value)
 }

 document.querySelector('#sair')
 .addEventListener('click', sair)

 document.querySelector('#close')
     .addEventListener('click', () => { closeModal(); clearInput() })

 document.querySelector('#cancelar')
     .addEventListener('click', () => { closeModal(); clearInput() })

     document.querySelector('#cancelar-saida')
     .addEventListener('click', () => { closeModalExit(); clearInput() })   

     document.querySelector('#close-saida')
     .addEventListener('click', () => { closeModalExit(); clearInput() })

 document.querySelector('#adicionar').addEventListener('click', saveClient)

 document.querySelector('#table').addEventListener('click', actionButttons)  

 document.querySelector('#placa').addEventListener('keyup',mask)
updateTable()