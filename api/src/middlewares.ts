import { NextFunction, Request, Response } from 'express';
import ErrorResponse from './api/interfaces/ErrorResponse';
// eslint-disable-next-line import/no-extraneous-dependencies
import { AnyZodObject } from 'zod';

export function notFound(req: Request, res: Response, next: NextFunction) {
    res.status(404);
    const error = new Error(`🔍 - Not Found - ${req.originalUrl}`);
    next(error);
}

export function errorHandler(
    err: Error,
    req: Request,
    res: Response<ErrorResponse>,
    // eslint-disable-next-line
    next: NextFunction
) {
    const statusCode = res.statusCode !== 200 ? res.statusCode : 500;
    res.status(statusCode);
    res.json({
        message: err.message,
        stack: process.env.NODE_ENV === 'production' ? '🥞' : err.stack,
    });
}

export const validate =
    (schema: AnyZodObject) =>
    async (req: Request, res: Response, next: NextFunction) => {
        try {
            await schema.parseAsync(req.body);
            return next();
        } catch (error) {
            return res.status(422).json(error);
        }
    };
