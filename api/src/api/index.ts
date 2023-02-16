import express from 'express';
import authRouter from './auth/auth.router';

const router = express.Router();

router.get<{}, { message: string }>('/', (req, res) => {
    res.json({
        message: 'Hello World',
    });
});

router.use('/auth', authRouter);

export default router;
