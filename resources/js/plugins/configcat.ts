import * as configcat from 'configcat-js'

const logger = configcat.createConsoleLogger(configcat.LogLevel.Warn)
export const configCatClient = configcat.getClient(
  import.meta.env.VITE_CONFIGCAT_SDK,
  configcat.PollingMode.AutoPoll,
  {
    logger: logger,
  }
)
