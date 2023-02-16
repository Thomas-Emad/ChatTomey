// eslint-disable-next-line import/no-extraneous-dependencies
import PocketBase from 'pocketbase';

export class PBase {
    private static instance: PocketBase;

    public static getInstance(): PocketBase {
        if (PBase.instance == null) {
            PBase.instance = new PocketBase(process.env.POCKETBASE_URL);
            return PBase.instance;
        }
        return PBase.instance;
    }
}
