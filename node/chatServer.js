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
    socket.on("LiveChat", (dataArray, rn) => {
        console.log("----------------------------------");
        console.log("----------- LiveChat -------------");
        console.log("----------------------------------");

        console.log(dataArray);

        // 받은 데이터가 배열인지, 비어있지 않은지 확인
        if (!Array.isArray(dataArray) || dataArray.length === 0) {
            return;
        }

        // room id: 파라미터 rn 또는 dataArray[0].rn 중 하나를 사용
        const roomId = rn || dataArray[0].rn;

        // 배열 자체를 그대로 전달
        io.to(roomId).emit("LiveChat", dataArray);
        io.emit("LiveChat", dataArray);

        console.log("----------------------------------");
        console.log("----------- LiveChat -------------");
        console.log("----------------------------------");
    });


    socket.on("NewClient", (dataArray) => {
        console.log("----------------------------------");
        console.log("----------- NewClient -------------");
        console.log("----------------------------------");

        const resArray = dataArray
            .map((data) => {
            // 1) 실제 들어온 키 확인
            console.log("data keys:", Object.keys(data));
            console.log("formatted_time value:", data.formatted_time);

            // 2) 구조분해 할당
            const {
                rn,
                msg,
                name = "",
                time,
                formatted_time, // 여기 이름이 정확한지 꼭 확인!
            } = data;

            // 3) Null 체크 (formatted_time 체크는 필요하면 빼도 됩니다)
            if (!rn || !msg || !time /*|| !formatted_time*/) {
                return null;
            }

            // 4) 반드시 return 블록 안에 객체를 return
            return {
                rn,
                msg,
                name,
                time,
                formatted_time, // 여기서도 key: value 쌍이 맞는지!
            };
            })
            // 5) null 필터
            .filter((item) => item !== null);

        console.log("resArray:", resArray);

        if (resArray.length > 0) {
            io.emit("NewClient", resArray);
        }

        console.log("----------------------------------");
        console.log("----------- NewClient -------------");
        console.log("----------------------------------");
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