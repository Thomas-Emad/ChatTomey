import app from './app';
import * as http from 'http';
// eslint-disable-next-line import/no-extraneous-dependencies
import * as socketio from 'socket.io';

const server = http.createServer(app);
const io = new socketio.Server(server, {
    cors: { origin: '*' },
});

const port = process.env.PORT || 5000;

io.on('connection', (socket: socketio.Socket) => {
    console.log(`logged in as ${socket.id}`);
    socket.on('message', (message: string, room) => {
        socket.to(room).emit('recieve-message', message);
    });
});

server.listen(port, () => {
    /* eslint-disable no-console */
    console.log(`Listening: http://localhost:${port}`);
    /* eslint-enable no-console */
});
