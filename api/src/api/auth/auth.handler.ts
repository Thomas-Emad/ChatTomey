import { Request, Response } from 'express';
import { PBase } from '../db';
import { User, AuthData } from './auth.validator';
import { ClientResponseError, RecordAuthResponse } from 'pocketbase';

export async function SignUpHandler(req: Request, res: Response<User>) {
    const user: User = req.body;
    const pb = PBase.getInstance();
    const signUpData = await pb.collection('users').create<User>(user);
    return res.json(signUpData);
}

export async function SignInHandler(
    req: Request,
    res: Response<RecordAuthResponse | object>,
) {
    const authData = await AuthData.parseAsync(req.body);
    const pb = PBase.getInstance();
    let signInData;
    try {
        signInData = await pb
            .collection('users')
            .authWithPassword(authData.email, authData.password);
    } catch (error) {
        if (error instanceof ClientResponseError) {
            res.status(error.status).json(error.data);
        }
    }
    return res.json(signInData);
}

export async function GetUserProfile(
    req: Request<{ user_id: string }>,
    res: Response,
) {
    const pb = PBase.getInstance();
    let record;
    try {
        record = await pb.collection('users').getOne<User>(req.params.user_id);
    } catch (error) {
        if (error instanceof ClientResponseError) {
            res.status(error.status).json(error.data);
        }
    }
    return res.json(record);
}

export async function DeleteUserProfile(
    req: Request<{ user_id: string }>,
    res: Response,
) {
    const pb = PBase.getInstance();
    try {
        await pb.collection('users').delete(req.params.user_id);
    } catch (error) {
        if (error instanceof ClientResponseError) {
            res.status(error.status).json(error.data).end();
        }
    }
    return res.status(200).json({});
}
