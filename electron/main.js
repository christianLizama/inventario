const { app, BrowserWindow } = require('electron')
const { spawn } = require('child_process')
const path = require('path')

let phpProcess = null

function startLaravel() {

    const isWindows = process.platform === 'win32'

    const phpPath = isWindows
        ? path.join(__dirname, '../php/php.exe')
        : 'php'

    phpProcess = spawn(phpPath, [
        '-S',
        '127.0.0.1:8000',
        '-t',
        'laravel/public'
    ], {
        cwd: path.join(__dirname, '..'),
        windowsHide: true
    })

    phpProcess.stdout.on('data', (data) => {
        console.log(`Laravel: ${data}`)
    })

    phpProcess.stderr.on('data', (data) => {
        console.error(`Laravel error: ${data}`)
    })
}

function createWindow () {

  const win = new BrowserWindow({
    width: 1200,
    height: 800,
    autoHideMenuBar: true
  })

  setTimeout(() => {
      win.loadURL('http://127.0.0.1:8000')
  }, 3000)
}

app.whenReady().then(() => {

    startLaravel()
    createWindow()

})

app.on('will-quit', () => {

    if (phpProcess) {
        phpProcess.kill()
    }

})