const selfUri    = 'https://www.jjjjjproject.com/';
const portNumber = 17000;
const fs         = require('fs');
const https      = require('https');
const express    = require('express');
const request    = require('request');
const app        = express();
const port       = process.env.PORT || portNumber;
const multer     = require('multer');
const bodyParser = require('body-parser');

const options = {
    key: fs.readFileSync('/etc/letsencrypt/live/jjjjjproject.com/privkey.pem'),
    cert: fs.readFileSync('/etc/letsencrypt/live/jjjjjproject.com/fullchain.pem')
};

const server = https.createServer(options, app);

const io = require('socket.io')(server, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"],
        transports: ['websocket', 'polling'],
        credentials: true
    },
    allowEIO3: true,
    pingInterval: 2000, // 마다 물어보기
    pingTimeout: 4000 // 동안 답안주면 끊기
});
app.use(bodyParser.json());

server.listen(port, function () {
    console.log('New client');
});

// JSON 유효성 검사
app.use((err, req, res, next) => { 
    if (err instanceof SyntaxError && err.status === 400 && 'body' in err) {
        const reqIP = req.headers['x-forwarded-for'] || req.connection.remoteAddress;
        const param = err.body;

        res.status(400).json({ success: false, msg: 'Invalid JSON format' });
    } else {
        next();
    }
});


io.on('connect', (socket) => {
    console.log('-----------------------------------')
    console.log('----------- connect ---------------')
    console.log('-----------------------------------')

    console.log('socketID :' + socket.id)

    // 실시간 채팅
    socket.on('LiveChat', (dataArray, rn) => {
        console.log('----------------------------------')
        console.log('----------- LiveChat -------------')
        console.log('----------------------------------')

        console.log(dataArray);

        const resArray = dataArray.map((data) => {
            const { rn, type, msg, name = '', time} = data;
            console.log('rn : ' + rn)
            console.log('type : ' + type)
            console.log('msg : ' + msg)
            console.log('name : ' + name)
            console.log('time : ' + time)

            // 만약 하나라도 값이 없거나 오지 않았다면 전달하지 않음
            if (!rn || !type || !msg || !time) {
                return null;
            }

            // 채팅 쏘기
            return {rn, type, msg, name, time};
        });

        // 값이 있는 객체만 filtering
        const filteredResArray = resArray.filter((item) => item !== null);

        // 필수값 다 온것만 에밋튜
        if (filteredResArray.length > 0) {
            io.to(rn).emit('LiveChat', filteredResArray); // 해당 룸에 메시지 전달
            io.emit('LiveChat', filteredResArray);
        }

        console.log('----------------------------------')
        console.log('----------- LiveChat -------------')
        console.log('----------------------------------')
    });

    socket.on('NewClient', (dataArray) => {
        console.log('----------------------------------')
        console.log('----------- NewClient -------------')
        console.log('----------------------------------')

        console.log(dataArray);

        const resArray = dataArray.map((data) => {
            const { rn, msg, name = '', time } = data;

            console.log('rn : ' + rn)
            console.log('msg : ' + msg)
            console.log('name : ' + name)
            console.log('time : ' + time)

            // 만약 하나라도 값이 없거나 오지 않았다면 전달하지 않음
            if (!rn || !msg || !time) {
                return null;
            }

            // 채팅 쏘기
            return { rn, msg, name, time };
        });

        // 값이 있는 객체만 filtering
        const filteredResArray = resArray.filter((item) => item !== null);

        // 필수값 다 온것만 에밋튜
        if (filteredResArray.length > 0) {
            io.emit('NewClient', filteredResArray);
        }

        console.log('----------------------------------')
        console.log('----------- NewClient -------------')
        console.log('----------------------------------')
    });
    //////////////////////////////////////
    //////////// ! 양방향 ////////////////
    //////////////////////////////////////




    // 클라이언트 소켓 연결 해제 처리, 스태프 알림
    socket.on('disconnect', () => {
        console.log('-------------------------------------')
        console.log('----------- disconnect --------------')
        console.log('-------------------------------------')

        console.log('-------------------------------------')
        console.log('----------- disconnect --------------')
        console.log('-------------------------------------')
    });
});