import { z } from 'zod';

export const User = z.object({
    username: z.string(),
    email: z.string().email(),
    emailVisibility: z.boolean(),
    password: z.string().min(8),
    passwordConfirm: z.string(),
    name: z.string(),
});

export type User = z.infer<typeof User>;

export const AuthData = z.object({
    email: z.string().email(),
    password: z.string(),
});

export type AuthData = z.infer<typeof AuthData>;
