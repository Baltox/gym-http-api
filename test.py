import gym, numpy, sys, math, random

env = gym.make("CartPole-v1")

eps = 1.0
states = []
rewards = []
reward_mean = []
next_states = []
actions = []

#for epi in range(150):
for epi in range(10):
    st = env.reset()

    env.render()

    reward = 0
    step = 0

    while step < 400 :

        #act = pickAction(st, eps)
        act = env.action_space.sample() # your agent here (this takes random actions)

        st2, reward, done, info = env.step(act)

        if(done) :
            reward = int(-1)
        else :
            reward = int(1)

        mask = [0, 0];
        mask[act] = 1;

        index = math.floor(random.uniform(0, 1) * len(states))

        states.insert(index, st)
        rewards.insert(index, [reward])
        reward_mean.insert(index, reward)
        next_states.insert(index, st2)
        actions.insert(index, mask)

        if (len(states) > 10000):
            states.pop(0)
            rewards.pop(0)
            reward_mean.pop(0)
            next_states.pop(0)
            actions.pop(0)

        st = st2
        step += 1

        if done:
            break

    eps = max(0.1, eps*0.99);

env.close()
