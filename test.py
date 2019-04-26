import gym, numpy

env = gym.make("CartPole-v1")

st = env.reset()

states = []

#for epi in range(150):
for epi in range(10):
    env.render()

    reward = 0
    step = 0

    while step < 400 :
        act = env.action_space.sample() # your agent here (this takes random actions)

        st2, reward, done, info = env.step(act)

        mask = [0, 0, 0];
        mask[act] = 1;

        states.append(st)

        if done:
            break

#observation = env.reset()

print(states)

env.close()
