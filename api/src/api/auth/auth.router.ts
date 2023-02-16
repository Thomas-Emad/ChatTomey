import express from 'express';
import { AuthData, User } from './auth.validator';
import { validate } from '../../middlewares';
import {
    DeleteUserProfile,
    GetUserProfile,
    SignInHandler,
    SignUpHandler,
} from './auth.handler';

const router = express.Router();

router.post<{}, User>('/sign-up', validate(User), SignUpHandler);
router.post('/sign-in', validate(AuthData), SignInHandler);
router.get<{ user_id: string }>('/profile/:user_id', GetUserProfile);
router.delete<{ user_id: string }>('/profile/:user_id', DeleteUserProfile);

export default router;
